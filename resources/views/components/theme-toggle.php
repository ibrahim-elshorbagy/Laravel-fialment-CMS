<div class="flex-inline">
    <div class="flex gap-2 p-1 border rounded-full">
        <!-- Button for Light Mode -->
        <button @click="theme = 'light'; localStorage.setItem('theme', 'light')"
            class="flex items-center justify-center w-6 h-6 rounded-full focus:outline-none"
            :class="theme === 'light'
                    ? 'bg-gray-100 text-gray-900 hover:text-gray-900 hover:bg-gray-200'
                    : 'text-gray-500 hover:text-gray-300 hover:bg-gray-700'">
            <i class="fa-regular fa-sun "></i>
        </button>
        <!-- Button for Dark Mode -->
        <button @click="theme = 'dark'; localStorage.setItem('theme', 'dark')"
            class="flex items-center justify-center w-6 h-6 rounded-full focus:outline-none"
            :class="theme === 'dark'
                    ? 'bg-gray-700 text-gray-300 hover:text-gray-300 hover:bg-gray-600'
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-200'">
            <i class="fa-regular fa-moon "></i>
        </button>
    </div>
</div>
