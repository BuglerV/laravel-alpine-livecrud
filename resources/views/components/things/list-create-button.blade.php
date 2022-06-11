@aware(['messages','emptyThing'])

	<x-jet-button
	  @click="confirmationOpen=false; currentThing={{ $emptyThing }}; informationOpen = true"
	  x-bind:disabled="off"
	  disabled
	  {{ $attributes }}
	>
		{{ $messages['thing.new'] }}
	</x-jet-button>