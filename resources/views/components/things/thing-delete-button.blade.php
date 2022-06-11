@aware(['messages'])

	<x-jet-button
	  @click="informationOpen=false;currentThing=thing;confirmOpen = true"
	  x-bind:disabled="off"
	  disabled
	  {{ $attributes }}
	>
		{{ $messages['button.delete'] }}
	</x-jet-button>
	