<template>
    <div
        class="search-wrapper flex justify-center border border-white bg-black"
    >
        <!-- Filter Button -->
        <div class="filter-search flex items-center px-2 py-2 md:p-3">
            <div class="filters relative flex items-center">
                <button
                    type="button"
                    class="filter-button flex w-full items-center justify-between gap-3 text-xl font-medium text-white sm:p-1 md:p-3 lg:p-5"
                    @click="toggleFilters"
                >
                    <span>Filters <span class="fas fa-filter"></span></span>
                    <svg
                        class="icon h-3 w-3"
                        :class="{ 'rotate-180': isFiltersVisible }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 10 6"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5 5 1 1 5"
                        />
                    </svg>
                </button>

                <!-- Filters Panel -->
                <div
                    class="filter-panel max-h-96 absolute w-full overflow-y-auto bg-gray-900 text-white transition-all"
                    v-show="isFiltersVisible"
                    @click.outside="isFiltersVisible = false"
                >
                    <!-- Band Types -->
                    <div>
                        <label>Preferred Band Types</label>
                        <div class="filter-options">
                            <div
                                v-for="option in bandTypeOptions"
                                :key="option"
                            >
                                <input
                                    type="checkbox"
                                    :value="option"
                                    v-model="selectedBandTypes"
                                />
                                <span>{{ option }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Genres -->
                    <div>
                        <label>Preferred Genres</label>
                        <div class="filter-options">
                            <div v-for="option in genreOptions" :key="option">
                                <input
                                    type="checkbox"
                                    :value="option"
                                    v-model="selectedGenres"
                                />
                                <span>{{ option }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div
                class="search-bar flex items-center justify-end rounded border border-white"
            >
                <input
                    class="search flex w-full justify-center bg-opac_black font-sans text-xl text-white"
                    type="search"
                    id="address-input"
                    name="search_query"
                    placeholder="Search..."
                    v-model="searchQuery"
                    @input="applyFilters"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "FilterComponent",
    props: {
        venues: Array,
        genres: Array,
        venuePromoterCount: Number,
    },
    data() {
        return {
            isFiltersVisible: true,
            searchQuery: "",
            selectedBandTypes: [],
            selectedGenres: [],
        };
    },
    methods: {
        toggleFilters() {
            this.isFiltersVisible = !this.isFiltersVisible;
        },
        applyFilters() {
            console.log("Filters Applied:", {
                searchQuery: this.searchQuery,
                bandTypes: this.selectedBandTypes,
                genres: this.selectedGenres,
            });
            // Emit an event or send an API request to apply filters
            this.$emit("filters-changed", {
                searchQuery: this.searchQuery,
                bandTypes: this.selectedBandTypes,
                genres: this.selectedGenres,
            });
        },
    },
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
