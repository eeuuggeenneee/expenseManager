<template>
    <div>
 

        <li class="menu-item text-white">
            <a @click="openModal">
                <i class="fa fa-key"></i> Change Password
            </a>
        </li>
       
        <Modal
            :visible="showModal"
            title="Change Password"
            @update:visible="showModal = false"
        >
            <template v-slot:content>
                <div class="mb-3">
                    <label for="password" class="form-label"
                        >New Password:</label
                    >
                    <input
                        type="password"
                        v-model="password"
                        id="password"
                        class="form-control"
                    />
                </div>
            </template>
            <template v-slot:actions>
                <button class="btn btn-primary" @click="submitChangePassword">
                    Change Password
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
            password: "",
            showModal: false,
        };
    },
    methods: {
        openModal() {
            this.showModal = true;
        },
        async submitChangePassword() {
            try {
                const response = await axios.post("/change-password", {
                    password: this.password,
                });
                if (response.data.message) {
                    alert(response.data.message);
                    this.password = "";
                    this.showModal = false;
                }
            } catch (error) {
                console.error(
                    "There was an error updating the password:",
                    error
                );
                if (error.response && error.response.data) {
                    alert(
                        error.response.data.message ||
                            "Error changing password."
                    );
                }
            }
        },
    },
};
</script>


