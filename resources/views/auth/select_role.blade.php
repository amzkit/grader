@extends('layouts.vuetify')

@section('content')
    <div id="app">
        <select_role user="{{ json_encode($user) }}"></select_role>
    </div>
@endsection
