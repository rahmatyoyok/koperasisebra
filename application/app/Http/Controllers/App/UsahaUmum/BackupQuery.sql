--Menghitung Laba PO
SELECT custom_data.*, (nilai_pekerjaan-bbm_konsumsi-total_po) as laba
FROM (select po.*,wo.nama_pekerjaan, wo.jenis_pekerjaan,wo.nilai_pekerjaan,
(Select sum(pd.harga) from po_details pd where pd.po_id = po.po_id) total_po,
u.`name` as operator,s.nama_supplier from purchase_orders po
inner join work_orders wo on wo.wo_id = po.wo_id
inner join users u on u.id = po.created_by
inner join suppliers s on s.supplier_id = po.supplier_id
where wo.status = 1) custom_data