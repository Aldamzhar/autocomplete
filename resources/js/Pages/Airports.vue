<template>
    <div class="autocomplete-container">
        <div class="search-box">
            <input
                type="text"
                v-model="search"
                @input="debouncedFetchAirports"
                @focus="showSuggestions = true"
                @blur="showSuggestions = false"
                placeholder="Search airports..."
                class="search-input"
                ref="searchInput"
            />
            <div v-if="showSuggestions && filteredSuggestions.length" class="suggestions-dropdown">
                <ul>
                    <li v-for="suggestion in filteredSuggestions" :key="suggestion.iata_code" @mousedown.prevent="selectAirport(suggestion)">
                        {{ suggestion.city_name_en }} ({{ suggestion.city_name_ru }}) ({{ suggestion.iata_code }})
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="results-table-container">
        <table class="results-table">
            <thead>
            <tr>
                <th>IATA Code</th>
                <th>City Name (EN)</th>
                <th>City Name (RU)</th>
                <th>Airport Name (EN)</th>
                <th>Airport Name (RU)</th>

            </tr>
            </thead>
            <tbody>
            <tr v-for="airport in airports" :key="airport.iata_code">
                <td>{{ airport.iata_code }}</td>
                <td>{{ airport.city_name_en }}</td>
                <td>{{ airport.city_name_ru }}</td>
                <td>{{ airport.airport_name_en }}</td>
                <td>{{ airport.airport_name_ru }}</td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<script>
import _ from 'lodash';

export default {
    name: 'Airports',
    data() {
        return {
            search: '',
            airports: [],
            showSuggestions: false,
            searchResults: [],
        };
    },
    computed: {
        filteredSuggestions() {
            if (!this.search) {
                return [];
            }
            return this.searchResults.filter(airport => {
                const searchLower = this.search.toLowerCase();
                return airport.city_name_en.toLowerCase().includes(searchLower) ||
                    airport.city_name_ru.toLowerCase().includes(searchLower) ||
                airport.iata_code.toLowerCase().includes(searchLower);
            });
        }
    },
    methods: {
        fetchAirports() {
            this.$inertia.get('/airports/search', { query: this.search }, {
                preserveState: true,
                onSuccess: page => {
                    this.searchResults = page.props.airports;
                    this.airports = this.searchResults;
                }
            });
        },
        selectAirport(airport) {
            this.search = airport.city_name_en || airport.city_name_ru || airport.iata_code;
            this.showSuggestions = false;
            this.airports = [airport];

            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        },
        debouncedFetchAirports: _.debounce(function() {
            this.fetchAirports();
        }, 500),
    },
    watch: {
        search(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.debouncedFetchAirports();
            }
        },
    },
    created() {
        this.fetchAirports();
        this.debouncedFetchAirports = _.debounce(this.fetchAirports, 500);
    }
};
</script>


<style scoped>
.autocomplete-container {
    position: relative;
    max-width: 600px;
    margin: 2rem auto 0;
}

.search-box {
    margin-bottom: 1rem;
}

.search-input {
    width: 100%;
    padding: 0.5rem;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.suggestions-dropdown {
    position: absolute;
    width: 100%;
    overflow-y: auto;
    max-height: 150px;
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.suggestions-dropdown ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.suggestions-dropdown li {
    padding: 10px;
    cursor: pointer;
}

.suggestions-dropdown li:hover {
    background-color: #f1f1f1;
}

.results-table-container {
    overflow-x: auto;
    width: 80%;
    margin: 0 auto;
}

.results-table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin: 0 auto;
}

.results-table th,
.results-table td {
    text-align: left;
    padding: 0.5rem;
    border-bottom: 1px solid #ccc;
}

.results-table th {
    background-color: #e9e9e9;
}

.results-table tr:hover {
    background-color: #f7f7f7;
}
</style>
