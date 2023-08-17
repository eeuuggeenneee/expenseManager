<template>
    <div class="col-lg-10 col-sm-12 offset-md-2 mt-3">
        <h2 class="mb-3">Expense Dashboard</h2>
        <div class="row">
            <!-- Expenses Table -->
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(total, categoryName) in categoryTotals"
                            :key="categoryName"
                        >
                            <td>{{ categoryName }}</td>
                            <td>{{ total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pie Chart -->
            <div class="col-md-6" style="width: 350px">
                <canvas ref="chart"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {
    Chart,
    PieController,
    ArcElement,
    CategoryScale,
    Tooltip,
    Legend,
} from "chart.js";
Chart.register(PieController, ArcElement, CategoryScale, Tooltip, Legend);
import { ref, onMounted, reactive } from "vue";

export default {
    setup() {
        const chart = ref(null);
        const state = reactive({
            expenses: [],
            categories: [],
            categoryTotals: {},
            chartData: null,
        });

        async function fetchData() {
            try {
                const [expensesResponse, categoriesResponse] =
                    await Promise.all([
                        axios.get("/api/expenses"),
                        axios.get("/api/expense-category"),
                    ]);
                state.expenses = expensesResponse.data;
                state.categories = categoriesResponse.data;
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        }

        function calculateTotals() {
            state.expenses.forEach((expense) => {
                const category = state.categories.find(
                    (cat) => cat.id === expense.expense_category_id
                );
                const categoryName = category
                    ? category.display_name
                    : "Unknown";
                if (!state.categoryTotals[categoryName]) {
                    state.categoryTotals[categoryName] = 0;
                }
                state.categoryTotals[categoryName] += parseFloat(
                    expense.amount
                );
            });

            state.chartData = {
                labels: Object.keys(state.categoryTotals),
                datasets: [
                    {
                        data: Object.values(state.categoryTotals),
                        backgroundColor: [
                            "#FF6384",
                            "#36A2EB",
                            "#FFCE56",
                            "#4CAF50",
                            "#E91E63",
                            "#2196F3",
                            "#FF9800",
                            "#9C27B0",
                            "#00BCD4",
                            "#8BC34A",
                        ],
                    },
                ],
            };
        }

        onMounted(async () => {
            await fetchData();
            calculateTotals();

            const ctx = chart.value.getContext("2d");
            new Chart(ctx, {
                type: "pie",
                data: state.chartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: "Expense Breakdown",
                    },
                },
            });
        });

        return { chart, categoryTotals: state.categoryTotals };
    },
};
</script>
