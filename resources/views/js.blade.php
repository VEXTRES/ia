<div>


    <div>
        <h1>Javascript</h1>
        <div>
            <form action="" id="form_tarea">
                <input type="text" id="input_tarea" placeholder="Agrega tu tarea aqui">
                <button type="submit" id="btn_submit">Enviar</button>
            </form>
        </div>
        <div>
            <ul id="lista" class="inline-flex">

            </ul>
            <label >Tus tarea </label> <span id="label_tarea"></span>
            <label for="">Tareas Restantes <span id="tareas_restantes"></span> </label>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded',function(){

            const form= document.getElementById('form_tarea')
            let input = document.getElementById('input_tarea')
            let btn = document.getElementById('btn_submit')
            let lista= document.getElementById('lista')
            let acumulador = document.getElementById('label_tarea')
            let tareas_less = document.getElementById('tareas_restantes')
            let i=0;
            let tareasRestantes=0;

            form.addEventListener("submit",function(e){
                e.preventDefault()
                const tarea = input.value.trim();

               const li=document.createElement('li')
               li.textContent=tarea
               const checkbox=document.createElement('input')
               checkbox.type="checkbox"
               checkbox.id=i
               const borrar=document.createElement('button')
               borrar.type="button"
               borrar.textContent="borrar"

               borrar.addEventListener('click',()=>{
                   lista.removeChild(li)
                   lista.removeChild(checkbox)
                   lista.removeChild(borrar)
                   i--;
                   acumulador.innerHTML=i;
               })

               checkbox.addEventListener('change',()=>{
                if(checkbox.checked){
                    tareasRestantes--;
                    tareas_less.innerHTML=tareasRestantes;
                }else{
                    tareasRestantes++;
                    tareas_less.textContent=tareasRestantes;
                }
                })
               lista.appendChild(li)
               lista.appendChild(checkbox)
               lista.appendChild(borrar)

                i++;
               tareasRestantes=i;
               tareas_less.textContent=tareasRestantes;
                acumulador.innerHTML=i;


            })




        })
    </script>


</div>
