<div class="media-body text-end">
    <label class="switch">
    <input type="checkbox" {{ $status==1 ? 'checked' : '' }} onChange="suspend({{ $id }},{{ $status }})">
    <span class="switch-state"> 
    </label>
</div>