<template>
    <div class="col-lg-10 offset-lg-2 col-md-12 col-sm-12 mt-3">
        <div v-if="flashMessage.visible" class="alert alert-success mt-3">
            {{ flashMessage.content }}
        </div>

        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-3">Expenses</h3>
                </div>
                <div class="col-md-6">
                    <h4 class="text-md-right">
                        Expense Management → Expenses List
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
                                    <th>ID</th>
                                    <th>Expense Category</th>
                                    <th>Amount</th>
                                    <th>Entry Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="expense in expenses"
                                    :key="expense.id"
                                    @dblclick="showEditModal(expense)"
                                >
                                    <td>{{ expense.id }}</td>
                                    <td>
                                        {{
                                            getCategoryName(
                                                expense.expense_category_id
                                            )
                                        }}
                                    </td>
                                    <td>{{ expense.amount }}</td>
                                    <td>{{ expense.entry_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <button
                class="btn btn-primary mb-3 mt-3"
                @click="showAddExpenseModal = true"
                style="float: right"
            >
                Add Expense
            </button>
        </div>

        <!-- Edit Expense Modal -->
        <Modal
            :visible="showEditExpenseModal"
            title="Edit Expense"
            @update:visible="showEditExpenseModal = false"
        >
            <template v-slot:content>
                <div class="mb-3">
                    <label for="editExpenseCategory" class="form-label"
                        >Expense Category:</label
                    >
                    <select
                        v-model="modalExpense.expense_category_id"
                        id="editExpenseCategory"
                        class="form-select"
                    >
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.display_name }}
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editExpenseAmount" class="form-label"
                        >Amount:</label
                    >
                    <input
                        type="number"
                        v-model="modalExpense.amount"
                        class="form-control"
                        id="editExpenseAmount"
                        step="0.01"
                    />
                </div>
                <div class="mb-3">
                    <label for="editExpenseEntryDate" class="form-label"
                        >Entry Date:</label
                    >
                    <input
                        type="date"
                        v-model="modalExpense.entry_date"
                        class="form-control"
                        id="editExpenseEntryDate"
                    />
                </div>
            </template>
            <template v-slot:actions>
                <button class="btn btn-danger" @click="deleteExpense">
                    Delete
                </button>
                <button class="btn btn-primary" @click="updateExpense">
                    Update
                </button>
            </template>
        </Modal>

        <!-- Add Expense Modal -->
        <Modal
            :visible="showAddExpenseModal"
            title="Add Expense"
            @update:visible="showAddExpenseModal = false"
        >
            <template v-slot:content>
                <div class="mb-3">
                    <label for="addExpenseCategory" class="form-label"
                        >Expense Category:</label
                    >
                    <select
                        v-model="newExpense.expense_category_id"
                        id="addExpenseCategory"
                        class="form-select"
                    >
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.display_name }}
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="addExpenseAmount" class="form-label"
                        >Amount:</label
                    >
                    <input
                        type="number"
                        v-model="newExpense.amount"
                        class="form-control"
                        id="addExpenseAmount"
                        step="0.01"
                    />
                </div>
                <div class="mb-3">
                    <label for="addExpenseEntryDate" class="form-label"
                        >Entry Date:</label
                    >
                    <input
                        type="date"
                        v-model="newExpense.entry_date"
                        class="form-control"
                        id="addExpenseEntryDate"
                    />
                </div>
            </template>
            <template v-slot:actions>
                <button class="btn btn-primary" @click="addExpense">Add</button>
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
            expenses: [],
            showAddExpenseModal: false,
            showEditExpenseModal: false,
            categories: [],
            selectedCategory: null,
            modalExpense: {
                expense_category_id: "",
                amount: "",
                entry_date: "",
            },
            newExpense: {
                expense_category_id: "",
                amount: "",
                entry_date: "",
            },
            flashMessage: {
                content: "",
                visible: false,
            },
        };
    },
    methods: {
        getCategoryName(categoryId) {
            const category = this.categories.find(
                (cat) => cat.id === categoryId
            );
            return category ? category.display_name : "N/A";
        },
        showEditModal(expense) {
            this.modalExpense = Object.assign({}, expense);
            this.showEditExpenseModal = true;
        },
        addExpense() {
            axios
                .post("/api/expenses", this.newExpense)
                .then((response) => {
                    this.expenses.push(response.data.expense); // Adding the new expense to the list
                    this.flashMessage.content =
                        response.data.message || "Expense added successfully!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    this.newExpense = {
                        // Resetting the newExpense data object
                        expense_category_id: "",
                        amount: "",
                        entry_date: "",
                    };
                    this.showAddExpenseModal = false;
                })
                .catch((error) => {
                    this.flashMessage.content =
                        error.response.data.message || "Error adding expense!";
                    this.flashMessage.visible = true;

                    setTimeout(() => {
                        this.flashMessage.visible = false;
                    }, 3000);

                    console.error("Error adding expense:", error.response);
                });
        },
        updateExpense() {
            const categoryID = this.modalExpense.id;
            axios
                .put(`/api/expenses/${categoryID}`, {
                    expense_category_id: this.modalExpense.expense_category_id,
                    amount: this.modalExpense.amount,
                    entry_date: this.modalExpense.entry_date,
                })
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message ||
                        "Expense Category updated successfully!";
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
        deleteExpense() {
            const categoryID = this.modalExpense.id;
            axios
                .delete(`/api/expenses/${categoryID}`)
                .then((response) => {
                    this.flashMessage.content =
                        response.data.message ||
                        "Expense Category deleted successfully!";
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
    },
    mounted() {
        axios.get("/api/expenses").then((response) => {
            this.expenses = response.data;
        });
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
    background-color: #fff; /* Add a background color here */
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
