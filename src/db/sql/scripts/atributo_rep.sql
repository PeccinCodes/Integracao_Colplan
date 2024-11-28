--UNION COM OS FATURADOS DO MES PASSADO
SELECT DISTINCT
       CASE
           WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet IN ('00','01') THEN 'MI'
           WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet = '02' THEN 'MI (S)'
           WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet IN ('03','04') THEN 'MI (I)'
           WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet IN ('00','01') THEN 'ME'
           WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet = '02' THEN 'ME (S)'
           WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet IN ('03','04') THEN 'ME (I)'
           ELSE NULL
       END  channel_code_level_1,
       pvac.territory_id     channel_code_level_2,
       decode(pvac.sales_channel_code,'INDIRECT','ATACADO','DIRECT','DISTRIBUIDOR',pvac.sales_channel_code ) channel_code_level_3,   
       CASE
           WHEN ftv.territory_code = 'BR' THEN TO_CHAR(pvac.CPF_CNPJ)
           ELSE TO_CHAR(pvac.PARTY_ID)
       END   channel_code_level_4,
       'REPRESENTANTE'        attribute_code,
       jres.resource_name     value  
  FROM ra_customer_trx_lines_all rctl,
		   ra_customer_trx_all       rct,
       pcn_vie_ar_clientes       pvac,
       ra_cust_trx_types_all     rctta,
       jtf_rs_salesreps          jrs,
       jtf_rs_resource_extns_vl  jres,
       fnd_territories_vl        ftv -- DESCRIÇÃO DO PAIS
 WHERE rctl.customer_trx_id      = rct.customer_trx_id
   AND rctl.line_type            = 'LINE'
   AND rct.status_trx            <> 'VD'
   AND rct.cust_trx_type_id      = rctta.cust_trx_type_id
	 AND rctta.attribute4          = 'S' -- somente faturamento
	 AND rct.ship_to_site_use_id   = pvac.site_use_id
	 AND rct.sold_to_customer_id   = pvac.cust_account_id
   AND pvac.country              = ftv.territory_code
   AND jrs.resource_id           = jres.resource_id
   AND jrs.salesrep_id           = pvac.primary_salesrep_id
   AND pvac.territory_id         <> '2001' -- retirada Peccin Direta
   AND pvac.CPF_CNPJ             NOT IN ('89425888000118','89425888000703','89425888000894','89425888000207','89425888000975','89425888001009')--RETIRADO CNPJ DA PECCIN
   --AND TRUNC(rct.trx_date)       BETWEEN '01/01/2021' AND TRUNC(SYSDATE);
   AND TRUNC(rct.trx_date)       BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-1),'MONTH') AND LAST_DAY(ADD_MONTHS(SYSDATE,-1))
   --AND TRUNC(pvac.DATA_ALTERACAO_CONTA) BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-1),'MONTH') AND LAST_DAY(ADD_MONTHS(SYSDATE,-1));
   
   
  UNION
  
--UNION COM ALTERACOES DO MES PASSADO
SELECT DISTINCT
       CASE
           WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet IN ('00','01') THEN 'MI'
           WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet = '02' THEN 'MI (S)'
           WHEN ftv.territory_code = 'BR' AND pvac.status_mercanet IN ('03','04') THEN 'MI (I)'
           WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet IN ('00','01') THEN 'ME'
           WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet = '02' THEN 'ME (S)'
           WHEN ftv.territory_code <> 'BR' AND pvac.status_mercanet IN ('03','04') THEN 'ME (I)'
           ELSE NULL
       END  channel_code_level_1,
       pvac.territory_id     channel_code_level_2,
       decode(pvac.sales_channel_code,'INDIRECT','ATACADO','DIRECT','DISTRIBUIDOR',pvac.sales_channel_code ) channel_code_level_3,   
       CASE
           WHEN ftv.territory_code = 'BR' THEN TO_CHAR(pvac.CPF_CNPJ)
           ELSE TO_CHAR(pvac.PARTY_ID)
       END   channel_code_level_4,
       'REPRESENTANTE'        attribute_code,
       jres.resource_name     value  
  FROM ra_customer_trx_lines_all rctl,
		   ra_customer_trx_all       rct,
       pcn_vie_ar_clientes       pvac,
       ra_cust_trx_types_all     rctta,
       jtf_rs_salesreps          jrs,
       jtf_rs_resource_extns_vl  jres,
       fnd_territories_vl        ftv -- DESCRIÇÃO DO PAIS
 WHERE rctl.customer_trx_id      = rct.customer_trx_id
   AND rctl.line_type            = 'LINE'
   AND rct.status_trx            <> 'VD'
   AND rct.cust_trx_type_id      = rctta.cust_trx_type_id
	 AND rctta.attribute4          = 'S' -- somente faturamento
	 AND rct.ship_to_site_use_id   = pvac.site_use_id
	 AND rct.sold_to_customer_id   = pvac.cust_account_id
   AND pvac.country              = ftv.territory_code
   AND jrs.resource_id           = jres.resource_id
   AND jrs.salesrep_id           = pvac.primary_salesrep_id
   AND pvac.territory_id         <> '2001' -- retirada Peccin Direta
   AND pvac.CPF_CNPJ             NOT IN ('89425888000118','89425888000703','89425888000894','89425888000207','89425888000975','89425888001009')--RETIRADO CNPJ DA PECCIN
   --AND TRUNC(rct.trx_date)       BETWEEN '01/01/2021' AND TRUNC(SYSDATE);
   --AND TRUNC(rct.trx_date)       BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-1),'MONTH') AND LAST_DAY(ADD_MONTHS(SYSDATE,-1))
   AND TRUNC(pvac.DATA_ALTERACAO_CONTA) BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-1),'MONTH') AND LAST_DAY(ADD_MONTHS(SYSDATE,-1))