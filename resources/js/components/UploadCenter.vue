<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Upload Center (Vue Version)
                        
                        <!-- TOMBOL CREATE MAPPING -->
                        <button
                            v-if="canCreateMapping"
                            class="btn btn-sm btn-primary"
                            @click="showMappingModal = true"
                        >
                            Create Mapping
                        </button>
                    </div>
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

    <!-- MODAL PREVIEW UPLOAD -->
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
                    <button type="button" class="btn btn-secondary" @click="cancelPreviewModal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="confirmImport" :disabled="isLoading">
                        {{ isLoading ? 'Importing...' : 'Confirm & Import' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CREATE MAPPING -->
    <div v-if="showMappingModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Mapping Format</h5>
                    <button type="button" class="btn-close" @click="closeMappingModal"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitNewMapping">
                        <div class="mb-3">
                            <label class="form-label">Mapping Code</label>
                            <input v-model="newMapping.code" type="text" class="form-control" placeholder="e.g., spotify_users_monthly" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input v-model="newMapping.description" type="text" class="form-control" placeholder="e.g., Monthly Spotify Users Report" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Database Table Name</label>
                            <input v-model="newMapping.table_name" type="text" class="form-control" placeholder="e.g., spotify_users" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Header starts at row</label>
                            <input v-model.number="newMapping.header_row" type="number" class="form-control" min="1" required>
                        </div>

                        <hr>
                        <h5>Column Mapping</h5>
                        <div v-for="(col, index) in newMapping.columns" :key="index" class="row mb-2">
                            <div class="col-md-3">
                                <input v-model="col.excel_column_index" type="text" class="form-control" placeholder="Excel Col (e.g., A)" required>
                            </div>
                            <div class="col-md-3">
                                <input v-model="col.table_column_name" type="text" class="form-control" placeholder="DB Column" required>
                            </div>
                            <div class="col-md-2">
                                <select v-model="col.data_type" class="form-select">
                                    <option value="string">String</option>
                                    <option value="integer">Integer</option>
                                    <option value="date">Date</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check mt-2">
                                    <input v-model="col.is_required" class="form-check-input" type="checkbox" :id="'required-' + index">
                                    <label class="form-check-label" :for="'required-' + index">Required</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button v-if="newMapping.columns.length > 1" type="button" class="btn btn-danger btn-sm" @click="removeColumn(index)">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" @click="addColumn">+ Add Column</button>

                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" @click="closeMappingModal">Cancel</button>
                            <button type="submit" class="btn btn-primary" :disabled="isLoading">
                                {{ isLoading ? 'Saving...' : 'Save Mapping' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { Modal } from 'bootstrap';

// MENERIMA PROPS DARI BLADE
const props = defineProps({
  canCreateMapping: {
    type: Boolean,
    default: false
  }
});

// DEBUGGING: Log props untuk memastikan diterima
console.log('Props canCreateMapping:', props.canCreateMapping);
console.log('Props type:', typeof props.canCreateMapping);

// State Management - Upload
const mappings = ref([]);
const selectedMappingCode = ref('');
const selectedFile = ref(null);
const fileHeaders = ref([]);
const previewData = ref([]);
const dynamicMapping = ref({});
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
let previewModalInstance = null;

// State Management - Create Mapping
const showMappingModal = ref(false);
const newMapping = ref({
    code: '',
    description: '',
    table_name: '',
    header_row: 1,
    columns: [
        {
            excel_column_index: '',
            table_column_name: '',
            data_type: 'string',
            is_required: false
        }
    ]
});

// Fetch initial mapping data
onMounted(async () => {
    try {
        const response = await axios.get('/api/mappings');
        mappings.value = response.data;
    } catch (error) {
        errorMessage.value = 'Failed to load mapping formats.';
        console.error('Error loading mappings:', error);
    }

    const modalEl = document.getElementById('previewModal');
    if (modalEl) {
        previewModalInstance = new Modal(modalEl, { keyboard: false, backdrop: 'static' });
    }
});

const availableDbColumns = computed(() => {
    if (!selectedMappingCode.value || !mappings.value.length) return [];
    const selected = mappings.value.find(m => m.code === selectedMappingCode.value);
    return selected ? selected.columns.map(c => c.table_column_name) : [];
});

// ===== UPLOAD FUNCTIONS =====
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

            previewModalInstance.show();
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
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        successMessage.value = response.data.message;
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'An error occurred during upload.';
    } finally {
        isLoading.value = false;
        previewModalInstance.hide();
    }
}

function cancelPreviewModal() {
    previewModalInstance.hide();
}

// ===== CREATE MAPPING FUNCTIONS =====
function closeMappingModal() {
    showMappingModal.value = false;
    // Reset form
    newMapping.value = {
        code: '',
        description: '',
        table_name: '',
        header_row: 1,
        columns: [
            {
                excel_column_index: '',
                table_column_name: '',
                data_type: 'string',
                is_required: false
            }
        ]
    };
    // Clear messages
    successMessage.value = '';
    errorMessage.value = '';
}

function addColumn() {
    newMapping.value.columns.push({
        excel_column_index: '',
        table_column_name: '',
        data_type: 'string',
        is_required: false
    });
}

function removeColumn(index) {
    newMapping.value.columns.splice(index, 1);
}

async function submitNewMapping() {
    isLoading.value = true;
    successMessage.value = '';
    errorMessage.value = '';

    try {
        const response = await axios.post('/api/mappings', newMapping.value);
        successMessage.value = 'Mapping format created successfully!';
        
        // Refresh daftar mappings
        const refreshResponse = await axios.get('/api/mappings');
        mappings.value = refreshResponse.data;
        
        // Close modal after 1.5 seconds
        setTimeout(() => {
            closeMappingModal();
        }, 1500);
        
    } catch (error) {
        if (error.response?.data?.errors) {
            // Validation errors
            const errors = error.response.data.errors;
            errorMessage.value = Object.values(errors).flat().join(', ');
        } else {
            errorMessage.value = error.response?.data?.message || 'Failed to create mapping format.';
        }
        console.error('Error creating mapping:', error);
    } finally {
        isLoading.value = false;
    }
}
</script>