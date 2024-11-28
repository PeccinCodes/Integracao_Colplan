CREATE OR REPLACE VIEW PCN_VW_INTEGRA_COLPLAN_FAT AS	
    SELECT ooh.order_number order_code,
        msib.segment1 sku_code,
        ool.ordered_quantity quantity,
        CASE 
        WHEN ftv.territory_code = 'BR' THEN REPLACE(ROUND(ool.unit_selling_price,2),',','.')
        WHEN ftv.territory_code <> 'BR' THEN REPLACE(ROUND(ool.unit_selling_price*rct.exchange_rate,2),',','.')
        ELSE NULL
        END gross_revenue,
        -- CALCULO LIQUIDO
        CASE 
        WHEN ftv.territory_code = 'BR' THEN
            REPLACE(ROUND(ool.unit_selling_price + (ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'ICMSLINEAMOUNTSUBST')),2) -
            ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'ICMSLINEAMOUNT')),2) -
            ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto_FCP ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'FCP')),2) -
            ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto_FCP ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'FCP_ST')),2) -
            ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'PIS')),2) -
            ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'COFINS')),2) -
            ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'IPILINEAMOUNT')),2) -
            (ROUND(SUM(GREATEST(NVL(rct.exchange_rate,0),1) * pcn_ar_imposto ( rctl.customer_trx_id, rctl.customer_trx_line_id, 'IMPOSTOS')),2)* -1))/ool.ordered_quantity,2),',','.')
        WHEN ftv.territory_code <> 'BR' THEN REPLACE(ROUND(ool.unit_selling_price*rct.exchange_rate,2),',','.')
        ELSE NULL
        END valor_liquido,                                                       
        TO_CHAR(rct.trx_date,'yyyy-mm-dd') date2,
        rct.trx_number code,
        CASE
            WHEN ftv.territory_code = 'BR' THEN TO_CHAR(pvac.CPF_CNPJ)
            ELSE null --TO_CHAR(pvac.PARTY_ID)
        END  recipient_identifier,
        CASE 
            WHEN rct.interface_header_attribute10 = 155 THEN '89425888000703'
            WHEN rct.interface_header_attribute10 = 195 THEN '89425888000118'
            ELSE NULL
        END issuing_identifier,
        CASE
            WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet IN ('00','01') THEN 'MI'
            WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet = '02' THEN 'MI (S)'
            WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet IN ('03','04') THEN 'MI (I)'
            WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet IN ('00','01') THEN 'ME'
            WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet = '02' THEN 'ME (S)'
            WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet IN ('03','04') THEN 'ME (I)'
            ELSE NULL
        END                   channel_code_level_1,
        pvac.territory_id     channel_code_level_2,
        decode(pvac.sales_channel_code,'INDIRECT','ATACADO','DIRECT','DISTRIBUIDOR',pvac.sales_channel_code ) channel_code_level_3,    
        CASE
            WHEN ftv.territory_code = 'BR' THEN TO_CHAR(pvac.CPF_CNPJ)
            ELSE null --TO_CHAR(pvac.PARTY_ID)
        END channel_code_level_4
	  FROM ra_customer_trx_lines_all rctl,
		   ra_customer_trx_all       rct,
		   pcn_vie_ar_clientes       pvac,
		   mtl_system_items_b        msib,
		   ra_cust_trx_types_all     rctta,
		   oe_order_lines_all        ool,
		   oe_order_headers_all      ooh,
           fnd_territories_vl        ftv
	 WHERE rctl.customer_trx_id           = rct.customer_trx_id
	   AND rctl.line_type                 = 'LINE'
	   AND rct.status_trx                 <> 'VD'         
	   AND rct.ship_to_site_use_id        = pvac.site_use_id
	   AND rct.sold_to_customer_id        = pvac.cust_account_id
	   AND rctl.inventory_item_id         = msib.inventory_item_id
	   AND msib.organization_id           = '195'
	   AND rct.cust_trx_type_id           = rctta.cust_trx_type_id
	   AND rctta.attribute4               = 'S' -- somente faturamento
	   AND rctl.interface_line_attribute6 = ool.line_id
	   AND ooh.header_id                  = ool.header_id
       AND pvac.country                   = ftv.territory_code
    -- AND ftv.territory_code             <> 'BR' --
     --AND msib.segment1                  = '99352'
       AND pvac.territory_id              <> '2001' -- retirada Peccin Direta
	   AND TRUNC(rct.trx_date)            BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-1),'MONTH') AND LAST_DAY(ADD_MONTHS(SYSDATE,-1))
     --and rct.trx_number = '49750'
  GROUP BY ooh.order_number,
		   msib.segment1,                           
		   TO_CHAR(rct.trx_date,'yyyy-mm-dd'), 
		   pvac.CPF_CNPJ,
		   pvac.state,
		   pvac.territory_id,
           ftv.territory_code,
		   pvac.sales_channel_code,
		   rct.trx_number,
           rct.exchange_rate,
           ool.unit_selling_price,
           msib.primary_uom_code,
           msib.inventory_item_id,
           pvac.status_mercanet,
           --TO_CHAR(pvac.PARTY_ID),
           ool.ordered_quantity,
           rct.interface_header_attribute10;