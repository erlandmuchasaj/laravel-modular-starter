@impersonating
<div class="alert alert-warning logged-in-as" style="border-radius: 0; margin: 0;">
	@lang('You are currently logged in as :name.', ['name' => auth()->user()->name ?? '']) <a href="{{ route('impersonate.leave') }}">@lang('Return to your account')</a>.
</div><!--alert alert-warning logged-in-as-->
@endImpersonating
