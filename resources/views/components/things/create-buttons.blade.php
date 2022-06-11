@aware(['messages'])

	<x-jet-secondary-button
	  @click="informationOpen = false"
	  class="mr-2"
	  x-bind:disabled="off"
	  disabled
	>
		{{ $messages['button.cancel'] }}
	</x-jet-secondary-button>
	<x-jet-button
	  @click="saveThing()"
	  x-bind:disabled="off"
	  disabled
	>
		{{ $messages['button.save'] }}
	</x-jet-button>