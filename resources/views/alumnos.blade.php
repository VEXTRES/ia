<div>
    <h1>Alumnos</h1>
   @foreach ($alumnos as $key=>$alumno)
    <form action="{{route('alumnos.destroy',$alumno->id)}}" method="post">
        @csrf
        @method('DELETE')
        <p>{{$alumno->name}}</p>
    <p>{{$alumno->email}}</p>
    <p>{{$alumno->fecha_nacimiento}}</p>
    <button>Eliminar</button>
    </form>
   @endforeach

   

    <form action="{{route('alumnos.store')}}" method="post">
        @csrf
        <input type="text" id="name" name="name" placeholder="name">
        <input type="text" id="email" name="email" placeholder="email">
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="fecha_nacimiento">
        <button>Guardar</button>
    </form>



</div>
