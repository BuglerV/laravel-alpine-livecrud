@aware(['messages'])

	<x-modal.information-modal>

		<x-slot name="content">
		  <div
			class="space-y-2"
			x-on:keydown.enter="saveThing()"
		  >
			  {{ $slot }}
		  </div>
		</x-slot>

		<x-slot name="actions">
			<x-livecrud::things.create-buttons />
		</x-slot>

	</x-modal.information-modal>