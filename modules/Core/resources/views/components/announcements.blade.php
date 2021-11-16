@if(isset($announcements) && $announcements->count())
    @foreach($announcements ?? [] as $announcement)
	    <div class="alert alert-{{ $announcement->type }} pt-1 pb-1  m-0 alert-dismissible fade in show" style="border-radius: 0; margin: 0;" role="alert">
	    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    	    <span aria-hidden="true">&times;</span>
	    	 </button>
	        {{ (new \Illuminate\Support\HtmlString($announcement->message)) }}
	    </div>
    @endforeach
@endif
