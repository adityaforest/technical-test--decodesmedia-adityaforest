<template>
  <div class="flex flex-col overflow-x-hidden">
    <div class="font-bold w-full h-fit">
      <h1 class="absolute m-auto top-[50vh] w-full text-[40px] bg-white text-blue-500 flex justify-center">WE ARE THE LEADER IN CONSTRUCTION INDUSTRY</h1>
      <img src="@/assets/images/construction-2.jpg" alt="" class="object-cover h-[100vh] w-full">  
    </div>
    <div class="flex flex-col px-12">
      <div class="flex gap-8 flex items-center mt-12 py-12">
        <p class="text-sm">
          Our company executes building and infrastructure works. We implement a futuristic
          style inspired by modern design and art. Our team is ready to construct anything
          that you depict in the drawing. We never stop constructing until our client is fully satisfied.        
        </p>
        <img src="@/assets/images/construction.jpg" alt="" width="500" class="rounded-lg shadow-2xl">        
      </div>
      <div class="flex gap-8 flex items-center mt-12 py-12 bg-gray-200 p-12 rounded-lg">
        <img src="@/assets/images/construction-3.jpg" alt="" width="500" class="rounded-lg shadow-2xl">        
        <p class="text-sm">
          Our company executes building and infrastructure works. We implement a futuristic
          style inspired by modern design and art. Our team is ready to construct anything
          that you depict in the drawing. We never stop constructing until our client is fully satisfied.        
        </p>
      </div>
      <div class="flex gap-8 flex items-center mt-12 py-12">
        <p class="text-sm">
          Our company executes building and infrastructure works. We implement a futuristic
          style inspired by modern design and art. Our team is ready to construct anything
          that you depict in the drawing. We never stop constructing until our client is fully satisfied.        
        </p>
        <img src="@/assets/images/construction-4.jpg" alt="" width="500" class="rounded-lg shadow-2xl">        
      </div>
    </div>

    <footer class="bg-gray-200 flex items-center justify-center text-sm p-12">
      <h1>@ 2023 Copyright COMPANY LLC</h1>
    </footer>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'landing-page'
})
</script>

<style>

</style>
