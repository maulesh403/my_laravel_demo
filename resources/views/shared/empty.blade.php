<tr>
    <td colspan="{{ $numCol }}" class="justify-content-center">
        <div class="alert alert-danger">
            @isset($message)
                {{ $message }}
            @else
                No record found.
            @endisset
        </div>
    </td>
</tr>