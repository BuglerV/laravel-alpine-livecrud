@props(['maxWidth' => null, 'openProp' => 'informationOpen'])

<x-livecrud::modal :maxWidth="$maxWidth" :openProp="$openProp">
	<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
			<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left grow">
			  @if(isset($title))
				<h3 class="text-lg">
					{{ $title }}
				</h3>
			  @endif

				<div class="mt-2">
					{{ $content }}
				</div>
			</div>
	</div>

	<div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
  @if(isset($actions))
	    {{ $actions }}
  @else
		<x-jet-secondary-button
		  @click="{{ $openProp }} = false;{{ $agreeJs ?? '' }}"
		>
			{{ __('Okey') }}
		</x-jet-secondary-button>
  @endif
	</div>
</x-livecrud::modal>
