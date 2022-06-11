@aware(['messages'])

	<x-jet-secondary-button
	  @click="confirmOpen=false;currentThing=thing;informationOpen = true"
	  x-bind:disabled="off"
	  disabled
	  {{ $attributes }}
	>
		{{ $messages['button.change'] }}
	</x-jet-secondary-button>
