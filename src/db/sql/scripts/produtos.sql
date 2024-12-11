WITH w_categorias AS (SELECT mic.inventory_item_id inventory_item_id,     
                          mic.organization_id   organization_id,  
                          ffl2.description      Grupo_Negocio,  
                          mic.segment2          ID_Grupo_Negocio,  
                          ffl3.description      Unidade_Negocio,  
                          mic.segment3          ID_Unidade_Negocio,  
                          ffl4.description      Marca,  
                          mic.segment4          ID_Marca,  
                          ffl5.description      Sabor,  
                          ffl6.description      Gramatura  
                     FROM mtl_item_categories_v     mic,  
                          fnd_flex_values_vl        ffl2,  
                          fnd_flex_values_vl        ffl3,  
                          fnd_flex_values_vl        ffl4,  
                          fnd_flex_values_vl        ffl5,  
                          fnd_flex_values_vl        ffl6  
                    WHERE mic.category_set_name     = 'Vendas e Marketing'  
                       
                      AND mic.segment2              = ffl2.flex_value(+)  
                      AND ffl2.flex_value_set_id    = 1011563                        
 
                      AND mic.segment3              = ffl3.flex_value(+)  
                      AND ffl3.flex_value_set_id    = 1011564                        
 
                      AND mic.segment4              = ffl4.flex_value(+)  
                      AND ffl4.flex_value_set_id    = 1011565                        
 
                      AND mic.segment5              = ffl5.flex_value(+)  
                      AND ffl5.flex_value_set_id    = 1011566                        
 
                      AND mic.segment6              = ffl6.flex_value(+)  
                      AND ffl6.flex_value_set_id    = 1011567  
                      AND mic.segment1              IN ('01'))  
    ,w_faturado AS (SELECT DISTINCT rctl.inventory_item_id item 
                      FROM ra_customer_trx_lines_all rctl, 
                           ra_customer_trx_all       rct, 
                           ra_cust_trx_types_all     rctta 
                     WHERE rctl.customer_trx_id   = rct.customer_trx_id 
                       AND rct.cust_trx_type_id   = rctta.cust_trx_type_id 
                       AND rctta.attribute4       = 'S' -- somente faturamento 
                       AND rctl.line_type         = 'LINE' 
                       AND rct.status_trx         <> 'VD' 
                       AND TRUNC(rct.trx_date)    BETWEEN '01-JAN-2024' and '30-JUN-2024')
                       --AND TRUNC(rct.trx_date)    BETWEEN '01-JUL-2024' and '31-DEZ-2024')
        ,w_ean AS (SELECT inventory_item_id,  
                          cross_reference  
                     FROM mtl_cross_references  
                    WHERE cross_reference_type = 'EAN13')  
                   SELECT  
                          --Comentado devido Piloto precisar da base com a configuracao nova e antiga
                          --nova
                          /*CASE 
                              WHEN msib.item_type = 'PAMI' AND wcc.ID_Grupo_Negocio = '01' AND msib.inventory_item_status_code <> 'Inactive' THEN '01 - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.ID_Grupo_Negocio = '01' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - 01 - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.ID_Grupo_Negocio = '02' AND msib.inventory_item_status_code <> 'Inactive' THEN '02 - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.ID_Grupo_Negocio = '02' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - 02 - MI' 
                              WHEN msib.item_type = 'PAME' AND wcc.ID_Grupo_Negocio = '01' AND msib.inventory_item_status_code <> 'Inactive' THEN '01 - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.ID_Grupo_Negocio = '01' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - 01 - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.ID_Grupo_Negocio = '02' AND msib.inventory_item_status_code <> 'Inactive' THEN '02 - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.ID_Grupo_Negocio = '02' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - 02 - ME' 
                              ELSE NULL 
                          END  n1, 
                          CASE 
                              WHEN msib.item_type = 'PAMI' AND wcc.Grupo_Negocio = 'CANDIES' AND msib.inventory_item_status_code <> 'Inactive' THEN 'CANDIES - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.Grupo_Negocio = 'CANDIES' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - CANDIES - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.Grupo_Negocio = 'CHOCOLATES' AND msib.inventory_item_status_code <> 'Inactive' THEN 'CHOCOLATES - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.Grupo_Negocio = 'CHOCOLATES' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - CHOCOLATES - MI' 
                              WHEN msib.item_type = 'PAME' AND wcc.Grupo_Negocio = 'CANDIES' AND msib.inventory_item_status_code <> 'Inactive' THEN 'CANDIES - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.Grupo_Negocio = 'CANDIES' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - CANDIES - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.Grupo_Negocio = 'CHOCOLATES' AND msib.inventory_item_status_code <> 'Inactive' THEN 'CHOCOLATES - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.Grupo_Negocio = 'CHOCOLATES' AND msib.inventory_item_status_code =  'Inactive' THEN 'INATIVO - CHOCOLATES - ME' 
                              ELSE NULL 
                          END  n1_desc, */
                          --antiga
                          CASE 
                              WHEN msib.item_type = 'PAMI' AND wcc.ID_Grupo_Negocio = '01' THEN '01 - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.ID_Grupo_Negocio = '02' THEN '02 - MI' 
                              WHEN msib.item_type = 'PAME' AND wcc.ID_Grupo_Negocio = '01' THEN '01 - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.ID_Grupo_Negocio = '02' THEN '02 - ME' 
                              ELSE '0' --NULL 
                          END  code_level_1, 
                          CASE 
                              WHEN msib.item_type = 'PAMI' AND wcc.Grupo_Negocio = 'CANDIES'    THEN 'CANDIES - MI' 
                              WHEN msib.item_type = 'PAMI' AND wcc.Grupo_Negocio = 'CHOCOLATES' THEN 'CHOCOLATES - MI' 
                              WHEN msib.item_type = 'PAME' AND wcc.Grupo_Negocio = 'CANDIES'    THEN 'CANDIES - ME' 
                              WHEN msib.item_type = 'PAME' AND wcc.Grupo_Negocio = 'CHOCOLATES' THEN 'CHOCOLATES - ME' 
                              ELSE '0' --NULL 
                          END  description_level_1, 
                          CASE  
                              WHEN wcc.Grupo_Negocio = 'CHOCOLATES' THEN wcc.ID_Unidade_Negocio || wcc.ID_Marca  
                              ELSE wcc.ID_Unidade_Negocio  
                          END code_level_2,  
                          CASE  
                           WHEN wcc.Grupo_Negocio = 'CHOCOLATES' THEN wcc.Unidade_Negocio || ' ' || wcc.Marca  
                           ELSE wcc.Unidade_Negocio  
                          END AS      description_level_2,  
                          wcc.ID_Marca code_level_3,  
                          wcc.Marca  description_level_3,  
                          msib.segment1    code_level_4,   
                          msib.description description_level_4,  
                          null multiple_lot,  
                          muc.to_uom_code secondary_conversion_unit,  
                          msib.primary_uom_code primary_conversion_unit,
                          REPLACE(ROUND((1/round(muc.conversion_rate,9)),6),',','.') conversion_factor,
                          REGEXP_REPLACE(NVL(mcr.cross_reference,0), '[[:space:]]*','')  ean_code 
                     FROM mtl_system_items_b        msib,  
                          mtl_uom_class_conversions muc,  
                          w_ean                     mcr,  
                          w_categorias              wcc,
                          w_faturado                wc
                    WHERE msib.organization_id            = 195 
                      AND wc.item                         = msib.inventory_item_id  
                      AND wcc.inventory_item_id           = msib.inventory_item_id  
                      AND wcc.organization_id             = msib.organization_id  
                      AND msib.inventory_item_id          = mcr.inventory_item_id(+)  
                      AND msib.inventory_item_id          = muc.inventory_item_id(+)
                      --AND trunc(msib.LAST_UPDATE_DATE) BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-4),'MONTH') AND LAST_DAY(ADD_MONTHS(SYSDATE,-1))