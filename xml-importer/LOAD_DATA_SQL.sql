LOAD DATA LOCAL INFILE 'PriceFull7290492000005-961-202003120010.gz.xml'
    INTO TABLE Items
	CHARACTER SET binary
	LINES STARTING BY '<ITEM>' TERMINATED BY '</ITEM>'
	(@item)
	SET	PriceUpdateDate = ExtractValue(@item, 'PRICEUPDATEDATE'), ItemCode = ExtractValue(@item, 'ITEMCODE'), ItemPrice = ExtractValue(@item, 'ITEMPRICE'), ItemName = IFNULL(ExtractValue(@item, 'ITEMNAME'), ExtractValue(@item, 'ITEMNM')), ManufacturerName = ExtractValue(@item, 'MANUFACTURERNAME'), ManufactureCountry = ExtractValue(@item, 'MANUFACTURECOUNTRY'), ManufacturerItemDescription = ExtractValue(@item, 'MANUFACTURERITEMDESCRIPTION'), UnitQty = ExtractValue(@item, 'UNITQTY'), Quantity = ExtractValue(@item, 'QUANTITY'), UnitOfMeasure = ExtractValue(@item, 'UNITOFMEASURE');