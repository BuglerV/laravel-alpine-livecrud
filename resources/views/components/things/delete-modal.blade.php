@aware(['messages','titleColumn'])

	<x-livecrud::modal.confirmation-modal>
		<x-slot name="title">
			{{ $messages['delete'] }}
		</x-slot>
		<x-slot name="content">
			{{ $messages['delete.confirm'] }}
			'<b x-text="currentThing?.{{ $titleColumn }}"></b>'.
		</x-slot>
		<x-slot name="agreeJs">
			deleteThing()
		</x-slot>
	</x-livecrud::modal.confirmation-modal>