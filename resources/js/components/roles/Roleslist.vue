<template>
    <div class="col-lg-10 offset-lg-2 col-md-12 col-sm-12 mt-3">
        <div v-if="flashMessage.visible" class="alert alert-success mt-3">
            {{ flashMessage.content }}
        </div>
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-3">Roles</h3>
                </div>
                <div class="col-md-6">
                    <h4 class="text-md-right">User Management → Roles List</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-10 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="role in roles"
                                    :key="role.id"
                                    @dblclick="showEditModal(role)"
                                >
                                    <td>{{ role.display_name }}</td>
                                    <td>{{ role.description }}</td>
                                    <td>{{ role.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <button
                class="btn btn-primary mb-3 mt-3 d-flex"
                @click="showAddRoleModal = true"
                style="float: right"
            >
                Add Role
            </button>
        </div>
    </div>

    <Modal
        :visible="showModal"
        :title="modalTitle"
        @update:visible="showModal = false"
    >
        <template v-slot:content>
            <label for="modalRoleDisplayName" class="form-label"
                >Display Name:</label
            >
            <input
                id="modalRoleDisplayName"
                type="text"
                v-model="modalRole.display_name"
                class="form-control"
            />
            <label for="modalRoleDescription" class="form-label mt-3"
                >Description:</label
            >
            <textarea
                id="modalRoleDescription"
                v-model="modalRole.description"
                class="form-control"
                rows="3"
            ></textarea>
        </template>
        <template v-slot:actions>
            <button class="btn btn-danger me-2" @click="deleteRole">
                Delete
            </button>
            <button class="btn btn-success" @click="updateRole">Update</button>
        </template>
    </Modal>

    <Modal
        :visible="showAddRoleModal"
        title="Add Role"
        @update:visible="showAddRoleModal = false"
    >
        <template v-slot:content>
            <div class="mb-3">
                <label for="addRoleDisplayName" class="form-label"
                    >Display Name:</label
                >
                <input
                    id="addRoleDisplayName"
                    type="text"
                    v-model="modalRole.display_name"
                    class="form-control"
                    placeholder="Enter role display name"
                />
            </div>
            <div class="mb-3">
                <label for="addRoleDescription" class="form-label"
                    >Description:</label
                >
                <textarea
                    id="addRoleDescription"
                    v-model="modalRole.description"
                    class="form-control"
                    rows="3"
                    placeholder="Enter role description"
                ></textarea>
            </div>
        </template>
        <template v-slot:actions>
            <button class="btn btn-primary" @click="addRole">Add</button>
        </template>
    </Modal>
    <!-- Add Role Button -->
</template>

<script>
import Modal from "../modal/Modal.vue";

import axios from "axios";
export default {
    components: {
        Modal,
    },
    data() {
        return {
            roles: [],
            showModal: false,
            showAddRoleModal: false,
            modalRole: {
                display_name: "",
                description: "",
            },
            modalTitle: "Edit Role",
            flashMessage: {
                content: "",
                type: "success",
                visible: false,
            },
        };
    },
    methods: {
        showEditModal(role) {
            this.modalRole = { ...role };
            this.showModal = true;
        },
        deleteRole() {
            const roleId = this.modalRole.id;

            axios
                .delete(`/api/roles/${roleId}`)
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message || "Role deleted successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.showModal = false;
                })
                .catch((error) => {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                    ) {
                        this.flashMessage.content = error.response.data.message;
                    } else {
                        this.flashMessage.content = "Error deleting role!";
                    }
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error deleting role", error);
                });
        },

        updateRole() {
            const roleId = this.modalRole.id;

            axios
                .put(`/api/roles/${roleId}`, {
                    display_name: this.modalRole.display_name,
                    description: this.modalRole.description,
                })
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message || "Role updated successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.showModal = false;
                })
                .catch((error) => {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                    ) {
                        this.flashMessage.content = error.response.data.message;
                    } else {
                        this.flashMessage.content = "Error updating role!";
                    }
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error updating role", error);
                });
        },

        addRole() {
            axios
                .post("/api/roles", this.modalRole)
                .then((response) => {
                    this.flashMessage.content = response.data.message;
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.$router.push("/roles");
                })
                .catch((error) => {
                    this.flashMessage.content = "Error creating role!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("There was an error:", error.response);
                });
        },
    },
    mounted() {
        axios.get("/api/roles").then((response) => {
            this.roles = response.data;
        });
    },
};
</script>

<style scoped>
.modal-backdrop.show {
    opacity: 1 !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
}

.modal-content {
    background-color: #fff;
    border: none;
    border-radius: 0.5rem;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: none;
}

.modal-title {
    color: #000;
}
.modal-body,
.modal-footer {
    padding: 20px;
}

.modal-footer button.btn {
    margin-right: 10px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}
</style>
