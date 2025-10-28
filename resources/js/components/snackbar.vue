<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  message: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: 'primary'
  },
  visible: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['update:visible']);
const isSnackbarVisible = ref(props.visible); // Create a local reactive variable

// Watch for changes in prop 'visible' and update local state
watch(() => props.visible, (newValue) => {
  isSnackbarVisible.value = newValue;
});
</script>

<template>
  <VSnackbar v-model="isSnackbarVisible" :color="props.color" location="top end"  transition="scale-transition">
    {{ props.message }}
    <template #actions>
      <VBtn color="error" @click="emit('update:visible', false)">
        Close
      </VBtn>
    </template>
  </VSnackbar>
</template>
