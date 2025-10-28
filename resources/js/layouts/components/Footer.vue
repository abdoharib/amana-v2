<template>
  <div class="h-100 d-flex align-center justify-md-space-between justify-center">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center text-medium-emphasis">
      &copy;
      {{ new Date().getFullYear() }}
     حقوق النشر محفوظة
      <VIcon
        icon="tabler-heart-filled"
        color="error"
        size="1.25rem"
        class="mx-1"
      />
      <a
        href="https://pixinvent.com"
        target="_blank"
        rel="noopener noreferrer"
        class="text-primary ms-1"
      >أمانة</a>
    </span>
  </div>
</template>
