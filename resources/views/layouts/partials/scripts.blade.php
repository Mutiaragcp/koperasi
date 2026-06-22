<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnToggle = document.getElementById("btnToggleSidebar");
        const sidebar   = document.getElementById("sidebar");
        const overlay   = document.getElementById("sidebarOverlay");

        function toggleMenu() {
            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        }

        if (btnToggle) btnToggle.addEventListener("click", toggleMenu);
        if (overlay)   overlay.addEventListener("click", toggleMenu);
    });
</script>

<!-- Global DataTables Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        if ($('.datatable-modern').length > 0) {
            $('.datatable-modern').DataTable({
                language: {
                    search: "Pencarian:",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                ordering: true,
                pageLength: 10
            });
        }
    });
</script>
@stack('scripts')