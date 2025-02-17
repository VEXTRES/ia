<div>
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Chat con IA</h2>

        <div id="chat-box" class="h-80 overflow-y-auto p-4 border border-gray-300 rounded mb-4 bg-gray-100">
            <div id="chat-messages">
                <!-- Aquí se mostrarán los mensajes -->
            </div>
        </div>

        <form id="chat-form">
            @csrf
            <input type="text" id="message" name="message" class="w-full p-2 border border-gray-300 rounded" placeholder="Escribe tu mensaje...">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 mt-2 rounded hover:bg-blue-600">Enviar</button>
        </form>
    </div>

    <script>
        document.getElementById('chat-form').addEventListener('submit', function(event) {
    event.preventDefault();

    let messageInput = document.getElementById('message');
    let message = messageInput.value.trim();
    if (message === '') return;

    let token = document.querySelector('input[name="_token"]').value;
    let chatBox = document.getElementById('chat-messages');

    // Mostrar mensaje del usuario
    chatBox.innerHTML += `<div class="text-right text-blue-600 mb-2"><strong>Tú:</strong> ${message}</div>`;
    messageInput.value = '';

    fetch("{{ route('preguntarIA') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({ message: message })
     })
    .then(response => {
        console.log('Status:', response.status);  // Debug status
        if (!response.ok) {
            return response.text().then(text => {
                console.log('Error texto:', text);  // Debug error
                throw new Error(`Error ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta:', data.message);  // Debug respuesta
        chatBox.innerHTML += `<div class="text-left text-green-600 mb-2">
    <strong>IA: </strong> ${data.message}
</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(error => {
        console.error("Error completo:", error);
        chatBox.innerHTML += `<div class="text-left text-red-600 mb-2"><strong>Error:</strong> ${error.message}</div>`;
    });
});
    </script>
</div>
