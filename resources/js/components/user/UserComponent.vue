<template>
    <div class="col-lg-10 offset-lg-2 col-md-12 col-sm-12 mt-3">
        <div v-if="flashMessage.visible" class="alert alert-success mt-3">
            {{ flashMessage.content }}
        </div>

        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-3">Users</h3>
                </div>
                <div class="col-md-6">
                    <h4 class="text-md-right">
                      User Management → User List
                    </h4>
                </div>
            </div>
        </div>
     

        <div class="col-lg-10 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                @dblclick="showEditModal(user)"
                            >
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    {{
                                        user.role
                                            ? user.role.display_name
                                            : "N/A"
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <button
                class="btn btn-primary mb-3 mt-3"
                @click="showAddUserModal = true"
                style="float: right"
            >
                Add User
            </button>
        </div>
    </div>

    <Modal
        :visible="showEditUserModal"
        title="Edit User"
        @update:visible="showEditUserModal = false"
    >
        <template v-slot:content>
            <label>Name:</label>
            <input v-model="modalUser.name" class="form-control" />
            <label>Email:</label>
            <input
                type="email"
                v-model="modalUser.email"
                class="form-control"
            />
            <label>Role:</label>
            <select v-model="selectedRole" class="form-control text-black">
                <option v-for="role in roles" :key="role.id" :value="role.id">
                    {{ role.display_name }}
                </option>
            </select>
        </template>
        <template v-slot:actions>
            <button class="btn btn-danger" @click="deleteUser">Delete</button>
            <button class="btn btn-primary" @click="updateUser">Update</button>
        </template>
    </Modal>

    <Modal
        :visible="showAddUserModal"
        title="Add User"
        @update:visible="showAddUserModal = false"
    >
        <template v-slot:content>
            <label>Name:</label>
            <input v-model="newUser.name" class="form-control" />
            <label>Email:</label>
            <input type="email" v-model="newUser.email" class="form-control" />
            <label>Role:</label>
            <select v-model="selectedRole" class="form-control text-black">
                <option v-for="role in roles" :key="role.id" :value="role.id">
                    {{ role.display_name }}
                </option>
            </select>
        </template>
        <template v-slot:actions>
            <button class="btn btn-primary" @click="addUser">Add</button>
        </template>
    </Modal>
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
            users: [],
            showAddUserModal: false,
            showEditUserModal: false,
            roles: [],
            selectedRole: null,
            modalUser: { name: "", email: "" },
            newUser: { name: "", email: "" },
            flashMessage: {
                content: "",
                visible: false,
            },
        };
    },
    methods: {
        showEditModal(user) {
            this.modalUser = Object.assign({}, user);
            this.selectedRole = user.role_id;
            this.showEditUserModal = true;
        },
        updateUser() {
            this.modalUser.role_id = this.selectedRole;
            axios
                .put(`/api/users/${this.modalUser.id}`, this.modalUser)
                .then((response) => {
                    this.flashMessage.content = response.data.message;
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.showEditUserModal = false;
                })
                .catch((error) => {
                    this.flashMessage.content =
                        error.response.data.message || "Error updating user!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error updating user:", error.response);
                });
        },
        deleteUser() {
            axios
                .delete(`/api/users/${this.modalUser.id}`)
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message || "User deleted successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.showEditUserModal = false;
                })
                .catch((error) => {
                    this.flashMessage.content =
                        error.response.data.message || "Error deleting user!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error deleting user:", error.response);
                });
        },
        addUser() {
            const userData = {
                name: this.newUser.name,
                email: this.newUser.email,
                role_id: this.selectedRole,
            };
            axios
                .post("/api/users", userData)
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message || "User added successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.showAddUserModal = false;
                })
                .catch((error) => {
                    this.flashMessage.content =
                        error.response.data.message || "Error adding user!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error adding user:", error.response);
                });
        },
    },
    mounted() {
        axios.get("/api/users").then((response) => {
            this.users = response.data;
        });
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
