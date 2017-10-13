@if($errors->any())

    <div class="error-detect">
        <div class="error text-center">
            @foreach($errors->all() as $error)
                <li class="alert alert-danger">{{ $error }}</li>
            @endforeach
        </div>
    </div>
@endif