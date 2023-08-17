<template>
    <div class="col-lg-10 offset-lg-2 col-md-12 col-sm-12 mt-3">
        <div v-if="flashMessage.visible" class="alert alert-success mt-3">
            {{ flashMessage.content }}
        </div>
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-3">Expense Category</h3>
                </div>
                <div class="col-md-6">
                    <h4 class="text-md-right">
                        Expense Management → Expense Category
                    </h4>
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
                                    v-for="category in categories"
                                    :key="category.id"
                                    @dblclick="showEditModal(category)"
                                >
                                    
                                    <td>{{ category.display_name }}</td>
                                    <td>{{ category.description }}</td>
                                    <td>{{ category.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button
                class="btn btn-primary mb-3 mt-3"
                @click="showAddCategoryModal = true"
                style="float: right"
            >
                Add Expense Category
            </button>
        </div>
    </div>

    <div class="col-lg-8 offset-lg-2 col-md-10 col-sm-12">
        <!-- Edit Category Modal -->
        <Modal
            :visible="showModal"
            :title="modalTitle"
            @update:visible="showModal = false"
        >
            <template v-slot:content>
                <label for="modalCategoryDisplayName" class="form-label"
                    >Display Name:</label
                >
                <input
                    id="modalCategoryDisplayName"
                    type="text"
                    v-model="modalCategory.display_name"
                    class="form-control"
                />
                <label for="modalCategoryDescription" class="form-label mt-3"
                    >Description:</label
                >
                <textarea
                    id="modalCategoryDescription"
                    v-model="modalCategory.description"
                    class="form-control"
                    rows="3"
                ></textarea>
            </template>
            <template v-slot:actions>
                <button class="btn btn-danger me-2" @click="deleteCategory">
                    Delete
                </button>
                <button class="btn btn-success" @click="updateCategory">
                    Update
                </button>
            </template>
        </Modal>

        <!-- Add Category Modal -->
        <Modal
            :visible="showAddCategoryModal"
            title="Add Expense Category"
            @update:visible="showAddCategoryModal = false"
        >
            <template v-slot:content>
                <div class="mb-3">
                    <label for="addCategoryDisplayName" class="form-label"
                        >Display Name:</label
                    >
                    <input
                        id="addCategoryDisplayName"
                        type="text"
                        v-model="modalCategory.display_name"
                        class="form-control"
                        placeholder="Enter category display name"
                    />
                </div>
                <div class="mb-3">
                    <label for="addCategoryDescription" class="form-label"
                        >Description:</label
                    >
                    <textarea
                        id="addCategoryDescription"
                        v-model="modalCategory.description"
                        class="form-control"
                        rows="3"
                        placeholder="Enter category description"
                    ></textarea>
                </div>
            </template>
            <template v-slot:actions>
                <button class="btn btn-primary" @click="addCategory">
                    Add
                </button>
            </template>
        </Modal>
    </div>
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
            categories: [],
            showModal: false,
            showAddCategoryModal: false,
            modalCategory: {
                display_name: "",
                description: "",
            },
            modalTitle: "Edit Expense Category",
            flashMessage: {
                content: "",
                type: "success",
                visible: false,
            },
        };
    },
    methods: {
        showEditModal(category) {
            this.modalCategory = { ...category };
            this.showModal = true;
        },
        deleteCategory() {
            const categoryID = this.modalCategory.id;
            axios
                .delete(`/api/expense-category/${categoryID}`)
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message ||
                        "Expense Category deleted successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.categories = this.categories.filter(
                        (category) => category.id !== categoryID
                    );
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
                        this.flashMessage.content =
                            "Error deleting expense category!";
                    }
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error deleting expense category", error);
                });
        },
        updateCategory() {
            const categoryID = this.modalCategory.id;
            axios
                .put(`/api/expense-category/${categoryID}`, {
                    display_name: this.modalCategory.display_name,
                    description: this.modalCategory.description,
                })
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message ||
                        "Expense Category updated successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    const index = this.categories.findIndex(
                        (category) => category.id === categoryID
                    );
                    if (index !== -1) {
                        this.categories[index] = this.modalCategory;
                    }
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
                        this.flashMessage.content =
                            "Error updating expense category!";
                    }
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error updating expense category", error);
                });
        },

        addCategory() {
            axios
                .post("/api/expense-category", this.modalCategory)
                .then((response) => {
                    this.flashMessage.content = response.data.message;
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.categories.push(this.modalCategory);

                    this.showAddCategoryModal = false;


                    this.$router.push("/expense_category");
                })
                .catch((error) => {
                    const errorMessage =
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                            ? error.response.data.message
                            : "Error creating Expense Category!";

                    this.flashMessage.content = errorMessage;
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);


        

                    

                    console.error("There was an error:", error.response);
                });
        },
    },
    mounted() {
        axios.get("/api/expense-category").then((response) => {
            this.categories = response.data;
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
