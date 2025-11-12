"""
数据库连接工具
对应原PHP项目的db.php和TD类
"""
import pymysql
from app.config import Config

class Database:
    """
    数据库连接类
    对应原PHP项目的TD类
    """
    _connection = None
    
    @classmethod
    def get_connection(cls):
        """
        获取数据库连接（单例模式）
        
        Returns:
            pymysql.Connection: 数据库连接对象
        """
        # 修复Bug #1: 检查连接是否有效
        if cls._connection is None:
            cls._connection = pymysql.connect(
                host=Config.DB_HOST,
                port=Config.DB_PORT,
                user=Config.DB_USER,
                password=Config.DB_PASSWORD,
                database=Config.DB_NAME,
                charset=Config.DB_CHARSET,
                cursorclass=pymysql.cursors.DictCursor,
                autocommit=False
            )
        else:
            # 检查连接是否仍然有效
            try:
                cls._connection.ping(reconnect=False)
            except:
                # 连接已断开，重新连接
                try:
                    cls._connection.close()
                except:
                    pass
                cls._connection = pymysql.connect(
                    host=Config.DB_HOST,
                    port=Config.DB_PORT,
                    user=Config.DB_USER,
                    password=Config.DB_PASSWORD,
                    database=Config.DB_NAME,
                    charset=Config.DB_CHARSET,
                    cursorclass=pymysql.cursors.DictCursor,
                    autocommit=False
                )
        return cls._connection
    
    @classmethod
    def close_connection(cls):
        """
        关闭数据库连接
        """
        if cls._connection:
            try:
                cls._connection.close()
            except:
                pass
            cls._connection = None
    
    @classmethod
    def execute_query(cls, sql, params=None):
        """
        执行查询SQL
        
        Args:
            sql (str): SQL语句
            params (tuple/dict): 参数
            
        Returns:
            list: 查询结果列表
        """
        conn = cls.get_connection()
        cursor = conn.cursor()
        try:
            if params:
                cursor.execute(sql, params)
            else:
                cursor.execute(sql)
            result = cursor.fetchall()
            return result if result else []
        except Exception as e:
            # 记录错误信息（可以后续添加日志）
            print(f"数据库查询错误: {str(e)}, SQL: {sql[:100]}")
            raise e
        finally:
            cursor.close()
    
    @classmethod
    def execute_update(cls, sql, params=None):
        """
        执行更新SQL（INSERT/UPDATE/DELETE）
        
        Args:
            sql (str): SQL语句
            params (tuple/dict): 参数
            
        Returns:
            int: 影响的行数
        """
        conn = cls.get_connection()
        cursor = conn.cursor()
        try:
            if params:
                cursor.execute(sql, params)
            else:
                cursor.execute(sql)
            conn.commit()
            return cursor.rowcount
        except Exception as e:
            conn.rollback()
            # 记录错误信息（可以后续添加日志）
            print(f"数据库更新错误: {str(e)}, SQL: {sql[:100]}")
            raise e
        finally:
            cursor.close()
    
    @classmethod
    def execute_insert(cls, sql, params=None):
        """
        执行插入SQL并返回插入的ID
        
        Args:
            sql (str): SQL语句
            params (tuple/dict): 参数
            
        Returns:
            int: 插入的ID
        """
        conn = cls.get_connection()
        cursor = conn.cursor()
        try:
            if params:
                cursor.execute(sql, params)
            else:
                cursor.execute(sql)
            conn.commit()
            return cursor.lastrowid
        except Exception as e:
            conn.rollback()
            # 记录错误信息（可以后续添加日志）
            print(f"数据库插入错误: {str(e)}, SQL: {sql[:100]}")
            raise e
        finally:
            cursor.close()

# 兼容原PHP代码的TD类
class TD:
    """
    兼容原PHP项目的TD类
    原PHP代码使用TD::conn()获取连接
    """
    @staticmethod
    def conn():
        """
        获取数据库连接
        
        Returns:
            pymysql.Connection: 数据库连接对象
        """
        return Database.get_connection()

