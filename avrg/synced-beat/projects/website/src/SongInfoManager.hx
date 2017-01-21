import php.db.Manager;

class SongInfoManager extends Manager<SongInfo>
{
    public static inline var PAGE_LENGTH = 20;

    public function new ()
    {
        super(SongInfo);
    }

    public function getPage (page :Int, ?query :String) :List<SongInfo>
    {
        var sql = new StringBuf();
        sql.add("SELECT * FROM ");
        sql.add(table_name);
        if (query != null) {
            filter(sql, query);
        }
        sql.add(" ORDER BY uploadedOn DESC ");
        sql.add(" LIMIT ");
        sql.add(PAGE_LENGTH);
        sql.add(" OFFSET ");
        sql.add(PAGE_LENGTH*page);
        return objects(sql.toString(), false);
    }

    function filter (sql :StringBuf, query :String)
    {
        query = quote("%" + query + "%");

        sql.add(" WHERE title LIKE ");
        sql.add(query);
        sql.add(" OR artist LIKE ");
        sql.add(query);
        sql.add(" OR uploaderName LIKE ");
        sql.add(query);
    }

    public function getSongCount (?query :String) :Int
    {
        var sql = new StringBuf();
        sql.add("SELECT COUNT(*) FROM ");
        sql.add(table_name);
        if (query != null) {
            filter(sql, query);
        }
        return execute(sql.toString()).getIntResult(0);
    }
}
