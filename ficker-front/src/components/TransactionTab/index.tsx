import { Col } from "antd";
import styles from "./transactiontab.module.scss";
import dayjs from "dayjs";
import Image from "next/image";

interface Transaction {
  id: number;
  user_id: number;
  category_id: number;
  card_id: number;
  description: string;
  date: Date;
  type: string;
  value: number;
  installments: number;
  created_at: Date;
  updated_at: Date;
}

interface TransactionTabProps {
  data: Transaction[];
}
export const TransactionTab = ({ data }: TransactionTabProps) => {
  return (
    <Col xs={20} lg={22}>
      <table className={styles.table}>
        <thead className={styles.thead}>
          <tr>
            <th>Editar</th>
            <th>Descrição</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Valor</th>
          </tr>
        </thead>
        <tbody>
          <>
            {data?.map((transaction) => (
              <tr key={transaction.id}>
                <td className={styles.tdEdit}>
                  <button style={{ background: "none", border: "none" }} onClick={() => {}}>
                    <Image src="/edit.png" alt="Editar" width={20} height={20} />
                  </button>
                </td>
                <td className={styles.tdDescription}>{transaction.description}</td>
                <td className={styles.tdDate}>{dayjs(transaction.date).format("DD/MM/YYYY")}</td>
                <td className={styles.tdCategory}>Outros</td>
                <td className={styles.tdValue} style={{ color: "red" }}>
                  -R${transaction.value}
                </td>
              </tr>
            ))}
          </>
        </tbody>
      </table>
    </Col>
  );
};
