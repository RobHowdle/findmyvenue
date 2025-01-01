// export function filterComponent() {
//     return {
//         // Data properties
//         isFiltersVisible: false,
//         searchQuery: "",
//         selectedBandTypes: [],
//         selectedGenres: [],
//         bandTypeOptions: [
//             { value: "all", label: "All Types" },
//             { value: "original-bands", label: "Original" },
//             { value: "cover-bands", label: "Covers" },
//             { value: "tribute-bands", label: "Tributes" },
//         ],
//         genreOptions: [
//             {
//                 value: "rock",
//                 label: "Rock",
//                 subgenres: ["Classic Rock", "Alternative Rock"],
//             },
//             {
//                 value: "pop",
//                 label: "Pop",
//                 subgenres: ["Dance Pop", "Synth Pop"],
//             },
//             {
//                 value: "jazz",
//                 label: "Jazz",
//                 subgenres: ["Smooth Jazz", "Free Jazz"],
//             },
//         ],

//         // Methods
//         toggleFilters() {
//             this.isFiltersVisible = !this.isFiltersVisible;
//         },

//         applyFilters() {
//             console.log("Search Query:", this.searchQuery);
//             console.log("Selected Band Types:", this.selectedBandTypes);
//             console.log("Selected Genres:", this.selectedGenres);

//             let filteredResults = venues.filter((venue) => {
//                 let matchBandType = this.selectedBandTypes.includes(
//                     venue.bandType
//                 );
//                 let matchGenres = this.selectedGenres.some((genre) =>
//                     venue.genres.includes(genre)
//                 );
//                 let matchSearchQuery = venue.name
//                     .toLowerCase()
//                     .includes(this.searchQuery.toLowerCase());

//                 return matchBandType && matchGenres && matchSearchQuery;
//             });

//             // You can now update the displayed list of venues with `filteredResults`
//             console.log(filteredResults);
//         },
//     };
// }
