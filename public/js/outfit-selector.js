Vue.component('outfit-selector', {
    props: {
        options: {
            type: Object,
            required: true
        },
        value: {
            type: Object,
            default: () => ({
                male: [],
                female: [],
                child: []
            })
        }
    },
    data() {
        return {
            selectedCategory: 'male'
        }
    },
    computed: {
        categories() {
            return Object.keys(this.options)
        },
        currentOptions() {
            return this.options[this.selectedCategory] || []
        },
        selectedValues() {
            return this.value[this.selectedCategory] || []
        }
    },
    methods: {
        toggleOption(value) {
            const index = this.selectedValues.indexOf(value)
            const newValues = [...this.selectedValues]

            if (index === -1) {
                newValues.push(value)
            } else {
                newValues.splice(index, 1)
            }

            this.$emit('input', {
                ...this.value,
                [this.selectedCategory]: newValues
            })
        },
        isSelected(value) {
            return this.selectedValues.includes(value)
        },
        switchCategory(category) {
            this.selectedCategory = category
        },
        getOutfitIcon(value) {
            const icons = {
                // Male outfits
                casual_tee: 'fas fa-tshirt',
                formal_suit: 'fas fa-user-tie',
                ethnic_kurta: 'fas fa-vest',
                sportswear: 'fas fa-running',
                business_casual: 'fas fa-briefcase',
                street_style: 'fas fa-hat-cowboy',

                // Female outfits
                floral_frock: 'fas fa-female',
                casual_denim: 'fas fa-vest-patches',
                saree_traditional: 'fas fa-gem',
                evening_gown: 'fas fa-star',
                business_formal: 'fas fa-user-tie',
                ethnic_lehenga: 'fas fa-ring',

                // Child outfits
                playful_casual: 'fas fa-child',
                school_uniform: 'fas fa-graduation-cap',
                party_dress: 'fas fa-gift',
                sports_kit: 'fas fa-futbol',
                winter_wear: 'fas fa-mitten',
                traditional_festive: 'fas fa-sun'
            }

            return icons[value] || 'fas fa-clothes-hanger'
        },
        hexToRgb(hex) {
            // Remove the hash if present
            hex = hex.replace('#', '')

            // Convert 3-digit hex to 6-digit
            if (hex.length === 3) {
                hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2]
            }

            // Convert hex to RGB
            const r = parseInt(hex.substring(0, 2), 16)
            const g = parseInt(hex.substring(2, 4), 16)
            const b = parseInt(hex.substring(4, 6), 16)

            return `${r},${g},${b}`
        }
    },
    template: `
        <div class="outfit-selector">
            <div class="btn-group mb-3">
                <button
                    v-for="category in categories"
                    :key="category"
                    type="button"
                    class="btn"
                    :class="{'btn-primary': selectedCategory === category, 'btn-outline-primary': selectedCategory !== category}"
                    @click="switchCategory(category)"
                >
                    {{ category.charAt(0).toUpperCase() + category.slice(1) }}
                </button>
            </div>

            <div class="row g-3">
                <div v-for="option in currentOptions" :key="option.value" class="col-md-4 col-sm-6">
                    <div
                        class="outfit-option rounded"
                        :class="{'selected': isSelected(option.value)}"
                        :style="{'--accent-color': option.color, '--accent-color-rgb': hexToRgb(option.color)}"
                        @click="toggleOption(option.value)"
                    >
                        <div class="outfit-content">
                            <div class="outfit-icon">
                                <i :class="getOutfitIcon(option.value)"></i>
                            </div>
                            <div class="form-check mb-2">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    :checked="isSelected(option.value)"
                                    @click.stop
                                >
                            </div>
                            <span class="outfit-label">{{ option.label }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="outfit" :value="JSON.stringify(value)">
        </div>
    `
})
