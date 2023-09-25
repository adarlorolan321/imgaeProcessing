<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Brgy </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #000;
           
        }

        table th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            margin-bottom: 50px;
        }

        .header {
            width: 100%;
            border-bottom: 5px solid #000;

        }

        .logo-left,
        .logo-right {
            width: 25%;
            text-align: center;
        }

        .center {
            width: 50%;
            text-align: center;
           
        }

        .footer {
            margin-top: 50px;
            text-align: left;
            
        }

        .footer p {
            margin: 5px 0;
        }

        .title {
            text-align: center;
            font-size: 25px;
            font-weight: 800;
        }

        .center-title {
            margin-top: 20px;
            text-align: center;
        }

        .header-title {
            font-size: 20px;
            color: black;
            font-weight: 800;
            letter-spacing: 2px;
            font-family: 'Times New Roman', Times, serif;
        }
        .header-title-big{
            font-size: 25px;
            color: black;
            font-weight: 800;
            letter-spacing: 1px;
            
        }

        .mimaropa {
            font-size: 16px;
            color: #ff0403;
            font-weight: 800;
        }

        .m-0 {
            margin: 0;
        }
        .mt-5{
            margin-top: 25px;
            margin-bottom: 0px;
        }

        .border-none td {
            border: none;

        }

        .date {
            font-weight: 800;
            margin-left: 20px;
        }

        .text-center {
            text-align: center;
        }

        .height-50 {
            padding-bottom: 50px;


        }
    </style>
</head>

<body>
    <table class="header border-none">
        <tr>
            <td class="logo-left">
            </td>
            <td class="center">
                <div class="center-title">
                    <p class="m-0">Republic of the Philippines</p>
                    <p class="m-0">Province of Oriental Mindoro</p>
                    <p class="header-title m-0">Municipality of Victoria</p>
                    <p class="header-title-big mt-5">Office of the Sangguniang Bayan</p>
                    <!-- <p class="m-0">Luna Bldg. III, Gov. Infantado St. , Calapan City, Oriental Mindoro</p>
                    <p class="m-0">Telefax Nos.: (043) 288-1717</p> -->
                </div>
            </td>
            <td class="logo-right">
            </td>
        </tr>
    </table>

    <!-- Display the HTML content from $data->description -->
    {!! $data->description !!}
</body>

</html>