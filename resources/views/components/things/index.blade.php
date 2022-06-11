@aware(['routePrefix','thingName','autoload','columns'])

<div class="mt-5 md:mt-0 md:col-span-2">
	<div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
	    <div
		  x-data="{
			things: [],
			off: false,
			confirmOpen: false,
			informationOpen: false,
			axios(method,uri,args={}){
			  if(this.off){ return }
			  this.off = true
			  return axios[method](uri,args)
	            .finally(()=>{ this.off = false })
			},
			loadAllThings(){
			  this.axios('get','{{ $routePrefix }}{{ $thingName }}').then(result => {
				this.things = result.data
			  })
			},
			currentThing:{title:'',description:'',price:''},
			deleteThing(){
			  if(!this.currentThing)
				return
			  this.axios('delete','{{ $routePrefix }}{{ $thingName }}/' + this.currentThing.id)
			  .then(()=>{
				this.things = this.things.filter((thing)=>{
				  return thing.id != this.currentThing.id
				})
			  })
			},
			saveThing(){
			  let thing = this.currentThing
			  let url = '{{ $routePrefix }}{{ $thingName }}'
			  let method = 'post'
			  if(thing.id){
				url += '/' + thing.id
				method = 'put'
			  }
			  
			  this.axios(method,url,thing).then(result=>{
				this.informationOpen = false
				if(thing.id){
			      this.things = this.things.map(thing=>{
					return thing.id == result.data.id
					  ? result.data
					  : thing
				  })
				  return
				}
				this.things.push(result.data)
				return
			  }).catch(error => {
				this.currentThing.errors = error.response.data.errors
			  })
			}
		  }"
	        @if($autoload)
		  x-init="loadAllThings()"
	        @endif
		>
		
			<x-dynamic-component :component="$listComponentName" />
			
			<x-livecrud::things.delete-modal />
			
			<x-livecrud::things.create-modal>
			  @foreach($columns as $name => $label)
				<x-livecrud::things.lainer name="{{ $name }}" label="{{ $label }}" />
			  @endforeach
			</x-livecrud::things.create-modal>
			
		</div>
	</div>
</div>
