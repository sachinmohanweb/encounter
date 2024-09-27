<ul class="action">
    <li class="edit">
        <form action="{{ route('admin.delete.book.thumb', ['id' => $id]) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE') <!-- This sets the form's method to DELETE -->
            <button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="submit">
                Delete
            </button>
        </form>
    </li>
</ul>