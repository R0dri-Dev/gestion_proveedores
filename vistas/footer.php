</div><!-- Cierre del contenedor principal -->

<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Luego Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para verificar que los modales funcionan -->
<script>
    $(document).ready(function() {
        console.log("jQuery cargado correctamente");
        
        // Comprobando si Bootstrap modal está disponible
        if (typeof $().modal === 'function') {
            console.log("Bootstrap modal está disponible");
        } else {
            console.log("Error: Bootstrap modal no está disponible");
        }
    });
</script>
</body>
</html>