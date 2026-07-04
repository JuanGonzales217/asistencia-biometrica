    </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Dashboard -->
<script src="../assets/js/dashboard.js"></script>

<script>

function actualizarHora(){

    const ahora = new Date();

    document.getElementById("horaActual").innerHTML =
        ahora.toLocaleTimeString('es-CO');

    document.getElementById("fechaActual").innerHTML =
        ahora.toLocaleDateString('es-CO',{
            weekday:'long',
            day:'numeric',
            month:'long',
            year:'numeric'
        });

}

actualizarHora();

setInterval(actualizarHora,1000);

</script>

</body>
</html>