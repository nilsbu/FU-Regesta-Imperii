<names>
{
for $x in distinct-values(fn:doc("data/index_full.xml")/list/document/issuer)
	order by $x
	return
		<name>
			{$x}
		</name>
}
</names>
