	<div>
		<x-jet-label
			for="{{ $name }}"
			value="{{ $label }}"
		/>
		<x-jet-input
			x-model="currentThing.{{ $name }}"
			type="{{ $type ?? 'text' }}"
			class="block w-3/4 mt-1"
			name="{{ $name }}"
			x-bind:readonly="off"
		/>
		<p class="mt-2 text-sm text-red-600"
		   x-text="currentThing?.errors?.{{ $name }}
						? currentThing.errors.{{ $name }}[0]
						: ''"
		></p>
	</div>