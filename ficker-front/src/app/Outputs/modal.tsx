import styles from "../EnterTransaction/entertransaction.module.scss";
import { Modal, Col, DatePicker, Row, Select, Form, Button } from "antd";
import type { DatePickerProps } from "antd";

interface OutputModalProps {
  isModalOpen: boolean;
  setIsModalOpen: (value: boolean) => void;
}

export const OutputModal = ({ isModalOpen, setIsModalOpen }: OutputModalProps) => {
  const showModal = () => {
    setIsModalOpen(true);
  };

  const handleOk = () => {
    setIsModalOpen(false);
  };

  const handleCancel = () => {
    setIsModalOpen(false);
  };

  const onChange: DatePickerProps["onChange"] = (date, dateString) => {
    console.log(date, dateString);
  };

  type FieldType = {
    description?: string;
    date?: string;
    type?: string;
    value: number;
    category_id: number;
    category_description: string;
  };

  return (
    <Modal
      title="Saídas"
      open={isModalOpen}
      onCancel={handleCancel}
      okButtonProps={{
        style: {
          display: "none",
        },
      }}
      cancelButtonProps={{
        style: {
          display: "none",
        },
      }}
    >
      <Form name="basic" onFinish={(values) => console.log(values)}>
        <Col>
          <label>Descrição</label>
          <Form.Item<FieldType> name="description">
            <input className={styles.input} style={{ width: "95%" }} />
          </Form.Item>
        </Col>
        <Col style={{ marginTop: 20 }}>
          <label>Data:</label>
          <Form.Item<FieldType> name="date">
            <DatePicker
              onChange={onChange}
              className={styles.input}
              placeholder="dd/mm/aaaa"
              format={"DD/MM/YYYY"}
            />
          </Form.Item>
        </Col>
        <Row style={{ marginTop: 20 }}>
          <Col>
            <label>Categoria:</label>
            <Form.Item name="category_id">
              <Select
                className={styles.input}
                style={{ width: 150, height: 35 }}
                options={
                  [
                    // { value: "1", label: "Alimentação" },
                    // { value: "2", label: "Educação" },
                  ]
                }
              />
            </Form.Item>
          </Col>
          <Col>
            <label>Descrição da Categoria:</label>
            <Form.Item name="category_description">
              <input className={styles.input} />
            </Form.Item>
          </Col>
        </Row>
        <Col style={{ marginTop: 20, marginBottom: 20 }}>
          <label>Valor:</label>
          <Form.Item name="value">
            <input className={styles.input} placeholder="R$" />
          </Form.Item>
        </Col>
        <Row>
          <Button className={styles.modalButtonWhite} onClick={handleCancel}>
            Cancelar
          </Button>
          <Button htmlType="submit" className={styles.modalButtonPurple}>
            Adicionar
          </Button>
        </Row>
      </Form>
    </Modal>
  );
};
