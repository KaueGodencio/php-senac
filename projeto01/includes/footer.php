<footer class="bs-primary border  " style=" position: fixed; bottom: 0;  left: 0; width: 100%;background-color: #221d66;padding: 1rem;text-align: center;
        z-index: 1000;">
    <div class="container  text-white">
        <p>Desenvolvido por Kauê Godêncio</p>


    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<!-- jQuery (necessário para DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- Inicializar DataTables -->
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "paging": true, // habilita a paginação
            "pageLength": 5, // quantos registros por página
            "lengthChange": false, // remove opção de alterar quantidade
            "searching": false, // remove o campo de pesquisa
            "info": false, // remove a informação de "Mostrando X de Y registros"
            "ordering": false // desativa ordenação das colunas
        });
    });
</script>

</body>

</html>