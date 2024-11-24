
<ul class="action">
     <li class="edit"> 
        <a href="{{route('admin.edit.gq_sub_category', ['id' => $id])}}">
            <i class="fas fa-pencil-alt"></i>
        </a>
    </li>
    <li class="delete">
        <a href="#"><i class="fa fa-trash" onClick="remove({{ $id }})"></i>
        </a>
    </li>
</ul>