<template>
	<input v-model="displayValue" @blur="handleInputState" @focus="handleInputState">
</template>

<script>
	export default {
		props: {
		  	value: null,
		    maskType: String,
	  	},
	  	data: function() {
    		return {
      			inputFocused: false
    		}
  		},
  		methods: {
  			handleInputState (event) {
    			this.inputFocused = event.type === 'focus'
    		},
    		unmask (value) {
    			return masks[this.maskType].unmask(value)
    		},
    		mask (value) {
    			return masks[this.maskType].mask(value)
    		},
  		},
  		computed: {
    		displayValue: {
      			get: function() {
        			if (this.inputFocused) {
          				return this.value.toString()
        			} else {
          				return this.mask(this.value)
        			}
      			},
      			set: function(modifiedValue) {        
        			this.$emit('input', this.unmask(modifiedValue))
      			}
    		}
  		}
	}
</script>