<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload Center (Vue Version)</div>
                    <div class="card-body">

                        <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
                        <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

                        <form @submit.prevent="triggerPreview">
                            <div class="mb-3">
                                <label for="mapping_code" class="form-label">Select Base Mapping Format</label>
                                <select v-model="selectedMappingCode" class="form-select" required>
                                    <option value="">-- Choose Format --</option>
                                    <option v-for="mapping in mappings" :key="mapping.code" :value="mapping.code">
                                        {{ mapping.description }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Choose Excel File</label>
                                <input class="form-control" type="file" @change="handleFileChange" required>
                            </div>
                            <button type="submit" class="btn btn-primary" :disabled="isLoading">
                                {{ isLoading ? 'Processing...' : 'Upload & Preview' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview & Map Columns</h5>
                </div>
                <div class="modal-body">
                    <p>Please map the Excel columns to the correct database columns.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th v-for="(header, index) in fileHeaders" :key="index">
                                        {{ header }}
                                        <select v-model="dynamicMapping[header]" class="form-select form-select-sm mt-1">
                                            <option value="">-- Don't Import --</option>
                                            <option v-for="dbCol in availableDbColumns" :key="dbCol" :value="dbCol">
                                                Save to -> {{ dbCol }}
                                            </option>
                                        </select>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, rowIndex) in previewData" :key="rowIndex">
                                    <td v-for="(cell, cellIndex) in row" :key="cellIndex">{{ cell }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cancelModal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="confirmImport" :disabled="isLoading">
                        {{ isLoading ? 'Importing...' : 'Confirm & Import' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { Modal } from 'bootstrap'; // <-- 1. TAMBAHKAN IMPORT INI

// State Management
const mappings = ref([]);
const selectedMappingCode = ref('');
const selectedFile = ref(null);
const fileHeaders = ref([]);
const previewData = ref([]);
const dynamicMapping = ref({});
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
let modalInstance = null;

// Fetch initial mapping data from Laravel backend when component is loaded
onMounted(async () => {
    try {
        const response = await axios.get('/api/mappings');
        mappings.value = response.data;
    } catch (error) {
        errorMessage.value = 'Failed to load mapping formats.';
    }

    // Inisialisasi modal bootstrap
    const modalEl = document.getElementById('previewModal');
    if (modalEl) {
        // 2. UBAH DARI 'new bootstrap.Modal' MENJADI 'new Modal'
        modalInstance = new Modal(modalEl, { keyboard: false, backdrop: 'static' });
    }
});

// ... sisa semua fungsi Anda (availableDbColumns, handleFileChange, dll.) tetap sama persis ...
const availableDbColumns = computed(() => {
    if (!selectedMappingCode.value || !mappings.value.length) return [];
    const selected = mappings.value.find(m => m.code === selectedMappingCode.value);
    return selected ? selected.columns.map(c => c.table_column_name) : [];
});

function handleFileChange(event) {
    selectedFile.value = event.target.files[0];
}

function triggerPreview() {
    if (!selectedFile.value) {
        alert('Please select a file.');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        if (jsonData.length > 0) {
            fileHeaders.value = jsonData.shift(); 
            previewData.value = jsonData.slice(0, 5);

            dynamicMapping.value = {};
            fileHeaders.value.forEach(header => {
                const cleanHeader = header.toLowerCase().replace(/ /g, '_');
                const match = availableDbColumns.value.find(dbCol => dbCol.toLowerCase() === cleanHeader);
                dynamicMapping.value[header] = match || '';
            });

            modalInstance.show();
        }
    };
    reader.readAsArrayBuffer(selectedFile.value);
}

async function confirmImport() {
    isLoading.value = true;
    successMessage.value = '';
    errorMessage.value = '';

    const formData = new FormData();
    formData.append('file', selectedFile.value);
    formData.append('mapping_code', selectedMappingCode.value);

    for (const key in dynamicMapping.value) {
        if (dynamicMapping.value[key]) {
            formData.append(`dynamic_mapping[${key}]`, dynamicMapping.value[key]);
        }
    }

    try {
        const response = await axios.post('/api/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        successMessage.value = response.data.message;
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'An error occurred during upload.';
    } finally {
        isLoading.value = false;
        modalInstance.hide();
    }
}

function cancelModal() {
    modalInstance.hide();
}
</script>