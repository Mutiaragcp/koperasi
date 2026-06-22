<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKOPSIM - Koperasi Modern</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    
    <!-- Modern CSS overrides -->
    <link href="/css/modern.css" rel="stylesheet">
    
    <!-- DataTables Global CSS -->
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <style>
        .dataTables_wrapper .row { 
            margin-bottom: 1rem; 
            align-items: center;
        }
        /* Fix untuk tabel yang menggunakan p-0 agar header/footer datatables tidak mepet */
        .p-0 .dataTables_wrapper .row:first-child {
            padding: 1.25rem 1.5rem 0.5rem 1.5rem;
            margin: 0;
        }
        .p-0 .dataTables_wrapper .row:last-child {
            padding: 0.5rem 1.5rem 1.25rem 1.5rem;
            margin: 0;
        }
        .dataTables_length select {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            margin: 0 0.5rem;
            display: inline-block;
            width: auto;
        }
        .dataTables_filter input {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.375rem 0.75rem;
            margin-left: 0.5rem;
            display: inline-block;
            width: auto;
        }
        .table-responsive { overflow-x: auto; }
    </style>
    
    @stack('css')
</head>
