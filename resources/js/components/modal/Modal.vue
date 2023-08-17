<template>
    <div v-if="visible" class="modal-backdrop show d-flex align-items-center justify-content-center">
      <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ title }}</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
  
            <div class="modal-body">
              <slot name="content"></slot>
            </div>
  
            <div class="modal-footer">
              <slot name="actions"></slot>
              <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: {
        type: String,
        default: ''
      },
      visible: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      closeModal() {
        this.$emit('update:visible', false);
      }
    }
  };
  </script>
  
  <style scoped>
  .modal-backdrop.show {
    opacity: 1 !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
  }
  
  .modal {
    display: block !important; /* To override Bootstrap's default display: none */
  }
  
  .modal-content {
    border-radius: 0.5rem;
  }
  
  .modal-header {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }
  
  .modal-title {
    margin-bottom: 0;
  }
  
  .modal-body {
    padding: 1rem;
  }
  
  .modal-footer {
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }
  
  .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
  }
  </style>
  