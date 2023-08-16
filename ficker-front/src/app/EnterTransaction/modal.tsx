"use client";
import { request } from "@/service/api";
import styles from "../EnterTransaction/entertransaction.module.scss";
import { Modal, Col, DatePicker, Row, Select, Form, Button, Input, message } from "antd";
import type { DatePickerProps } from "antd";
import { useEffect, useState } from "react";
import dayjs from "dayjs";

interface EnterTransactionModalProps {
  isModalOpen: boolean;
  setIsModalOpen: (value: boolean) => void;
}

export const EnterTransactionModal = ({ isModalOpen, setIsModalOpen }: EnterTransactionModalProps) => {
  const [showDescriptionCategory, setShowDescriptionCategory] = useState(false);
  const [form] = Form.useForm();

  const handleCancel = () => {
    setIsModalOpen(false);
    form.resetFields();
  };

  const handleFinish = async () => {
    try {
      const values = await form.validateFields();
      console.log(dayjs(values.date).format("YYYY-MM-DD"));
      await request({
        method: "POST",
        endpoint: "transaction",
        data: {
          ...values,
          date: dayjs(values.date).format("YYYY-MM-DD"),
          type: "entrada",
        },
      });
      message.success("Transação adicionada com sucesso!");
      handleCancel();
    } catch (errorInfo) {
      message.error("Erro ao adicionar transação!");
    }
  };
  const onChange: DatePickerProps["onChange"] = (date, dateString) => {
    console.log(date, dateString);
  };

  useEffect(() => {
    form.resetFields();
  }, []);

  return (
    <Modal
      title="Nova Entrada"
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
      <Form
        form={form}
        name="basic"
        onFinish={handleFinish}
        onFinishFailed={(errorInfo) => console.log(errorInfo)}
        onValuesChange={(changedValues) => {
          if (Object.keys(changedValues)[0] === "category_id") {
            setShowDescriptionCategory(changedValues.category_id === 0);
          }
        }}
      >
        <Col>
          <label>Descrição</label>
          <Form.Item
            name="description"
            rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
          >
            <Input className={styles.input} style={{ width: "95%" }} />
          </Form.Item>
        </Col>
        <Col style={{ marginTop: 20 }}>
          <label>Data:</label>
          <Form.Item name="date" rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}>
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
            <Form.Item
              name="category_id"
              rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
            >
              <Select
                className={styles.input}
                style={{ width: 200, height: 35 }}
                options={[
                  { value: 0, label: "Nova" },
                  { value: 1, label: "Ao" },
                ]}
              />
            </Form.Item>
          </Col>
            <Col>
              <label>Descrição da Categoria:</label>
              <Form.Item
                name="category_description"
                rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
              >
                <Input className={styles.input} />
              </Form.Item>
            </Col>
        </Row>
        <Col style={{ marginBottom: 20 }} xl={15}>
          <label>Valor:</label>
          <Form.Item name="value" rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}>
            <Input className={styles.input} placeholder="R$" />
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
