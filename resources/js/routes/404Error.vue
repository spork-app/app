<template>
    <div class="w-full h-full flex flex-col items-center justify-center ">
        <div class="h-20 flex items-center justify-center gap-4">
            <div class="mb-8 text-5xl font-bold text-indigo-600">404</div>
            <hr class="border-l border-gray-300 h-full" />

            <div class="flex flex-col gap-2">
                <div class="text-5xl font-bold text-gray-900">Page not found</div>
                <div class="text-lg text-gray-500">Please check the URL and try again.</div>
            </div>
        </div>
        <div class="mt-8 flex gap-4">
            <router-link to="/planning" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Home
            </router-link>

            
            <router-link to="/login" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-indigo-600 border-indigo-600 hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Contact us
            </router-link>

        </div>
    </div>
</template>