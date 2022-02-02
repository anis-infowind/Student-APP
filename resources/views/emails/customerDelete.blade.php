<style>
table.store-info, table.customer-info {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
.store-info td, .store-info th, .customer-info td, .customer-info th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
.store-info tr:nth-child(even), .customer-info tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
<h2>Customer ({{ $details['customer_email'] }}) requested to delete their data on store {{ $details['shop_domain'] }}</h2>
<h3>Store Information:</h3>
<table style="width:100%;font-family: arial, sans-serif;border-collapse: collapse;" class"store-info">
    <tr>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Shop Id</th>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{ $details['shop_id'] }}</td>
    </tr>
    <tr style="background-color: #dddddd;">
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Shop Domain</th>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{ $details['shop_domain'] }}</td>
    </tr>
</table>

<h3>Customer Information:</h3>
<table style="width:100%;font-family: arial, sans-serif;border-collapse: collapse;" class"customer-info">
    <tr>
        <th>Customer Id</th>
        <td>$details['customer_id'] }}</td>
    </tr>
    <tr>
        <th>Customer Email</th>
        <td>$details['customer_email'] }}</td>
    </tr>
    <tr>
        <th>Customer phone</th>
        <td>$details['customer_phone'] }}</td>
    </tr>
    <tr>
        <th>Customer orders</th>
        <td>$details['customer_orders'] }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Customer Id</th>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{ $details['customer_id'] }}</td>
    </tr>
    <tr style="background-color: #dddddd;">
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Customer Email</th>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{ $details['customer_email'] }}</td>
    </tr>
    <tr>
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Customer phone</th>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{ $details['customer_phone'] }}</td>
    </tr>
    <tr style="background-color: #dddddd;">
        <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Customer orders</th>
        <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">{{ $details['customer_orders'] }}</td>
    </tr>
</table>